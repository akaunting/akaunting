<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use App\Models\Common\Media;
use App\Traits\Uploads as Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Uploads extends Controller
{
    use Helper;

    /**
     * Get the specified resource.
     *
     * @param  $id
     * @return mixed
     */
    public function get($id)
    {
        try {
            $media = Media::find($id);
        } catch (\Exception $e) {
            return response(null, 204);
        }

        // Authorization: prevent same-company IDOR (CWE-639/862).
        if (! $this->authorizeMedia($media)) {
            return response(null, 204);
        }

        // Get file path
        if (!$this->getMediaPathOnStorage($media)) {
            return response(null, 204);
        }

        return $this->streamMedia($media);
    }

    public function inline($id)
    {
        try {
            $media = Media::find($id);
        } catch (\Exception $e) {
            return response(null, 204);
        }

        // Authorization: prevent same-company IDOR (CWE-639/862).
        if (! $this->authorizeMedia($media)) {
            return response(null, 204);
        }

        // Get file path
        if (!$this->getMediaPathOnStorage($media)) {
            return response(null, 204);
        }

        return $this->streamMedia($media, 'inline');
    }

    /**
     * Get the specified resource.
     *
     * @param  $id
     * @return mixed
     */
    public function show($id, Request $request)
    {
        $file = false;
        $options = false;
        $column_name = 'attachment';

        if ($request->has('column_name')) {
            $column_name = $request->get('column_name');
        }

        if ($request->has('page')) {
            $options = [
                'page' => $request->get('page'),
                'key' => $request->get('key'),
            ];
        }

        try {
            $media = Media::find($id);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => true,
                'data'    => [],
                'message' => 'null',
                'html'    => '',
            ]);
        }

        // Authorization: prevent same-company IDOR (CWE-639/862).
        if (! $this->authorizeMedia($media)) {
            return response()->json([
                'success' => false,
                'error'   => true,
                'data'    => [],
                'message' => 'null',
                'html'    => '',
            ]);
        }

        // Get file path
        if (!$this->getMediaPathOnStorage($media)) {
            return response()->json([
                'success' => false,
                'error'   => true,
                'data'    => [],
                'message' => 'null',
                'html'    => '',
            ]);
        }

        $file = $media;

        $html = view('components.media.file', compact('file', 'column_name', 'options'))->render();

        return response()->json([
            'success' => true,
            'error'   => false,
            'data'    => [],
            'message' => 'null',
            'html'    => $html,
        ]);
    }

    /**
     * Download the specified resource.
     *
     * @param  $id
     * @return mixed
     */
    public function download($id)
    {
        try {
            $media = Media::find($id);
        } catch (\Exception $e) {
            return false;
        }

        // Authorization: prevent same-company IDOR (CWE-639/862).
        if (! $this->authorizeMedia($media)) {
            return false;
        }

        // Get file path
        if (!$this->getMediaPathOnStorage($media)) {
            return false;
        }

        return $this->streamMedia($media);
    }

    /**
     * Destroy the specified resource.
     *
     * @param  $id
     * @return callable
     */
    public function destroy($id, Request $request)
    {
        $return = back();

        if ($request->has('ajax') && $request->get('ajax')) {
            $return = [
                'success' => true,
                'errors' => false,
                'message' => '',
                'redirect' => $request->get('redirect')
            ];
        }

        try {
            $media = Media::find($id);
        } catch (\Exception $e) {
            return $return;
        }

        // Get file path
        if (!$path = $this->getMediaPathOnStorage($media)) {
            $message = trans('messages.warning.deleted', ['name' => $media->basename, 'text' => $media->basename]);

            flash($message)->warning()->important();

            return $return;
        }

        $media->delete(); //will not delete files

        Storage::delete($path);

        if (!empty($request->input('page'))) {
            switch ($request->input('page')) {
                case 'setting':
                    setting()->set($request->input('key'), '');

                    setting()->save();
                    break;
            }
        }

        return $return;
    }

    /**
     * Authorize media access to prevent IDOR (CWE-639/862).
     *
     * The common uploads routes sit behind plain `auth` middleware with no
     * permission check. The Media `Tenants` trait scopes by company_id so
     * cross-company access is blocked, but any same-company low-privilege user
     * (including a portal customer) could enumerate media IDs to read other
     * users' files (invoice attachments, bills, receipts, etc.).
     *
     * Policy:
     *  - Admin users (read-admin-panel): allowed — they have legitimate
     *    company-wide file access.
     *  - Portal users (read-client-portal): allowed only if the media is
     *    attached to a mediable parent whose `contact_id` matches the portal
     *    user's own contact. This mirrors the existing ownership checks in
     *    Portal\InvoiceShow / Portal\PaymentShow FormRequests.
     *  - Otherwise: denied (403 / 204).
     *
     * @param  Media  $media
     * @return bool
     */
    private function authorizeMedia($media): bool
    {
        if (! is_object($media)) {
            return false;
        }

        $user = user();

        // Defensive: auth middleware guarantees a user, but guard against
        // any edge-case invocation without one (default-deny).
        if (! $user) {
            return false;
        }

        // Admin users have company-wide file access.
        if ($user->can('read-admin-panel')) {
            return true;
        }

        // Portal users: check ownership via the mediable parent's contact_id.
        if ($user->isCustomer()) {
            return $this->mediaBelongsToContact($media, $user);
        }

        // Any other authenticated user without admin/portal access: deny.
        return false;
    }

    /**
     * Determine whether the given media is attached to a mediable record
     * owned by the portal user's contact.
     *
     * Resolves the polymorphic mediables pivot for the media and, for each
     * parent model that has a `contact_id` column, checks whether it matches
     * the user's contact_id. If no mediable parent exists or none has a
     * contact_id, access is denied (default-deny).
     *
     * @param  Media  $media
     * @param  \App\Models\Auth\User  $user
     * @return bool
     */
    private function mediaBelongsToContact($media, $user): bool
    {
        $contact = $user->contact;

        if (! $contact) {
            return false;
        }

        $rows = DB::table(config('mediable.mediables_table', 'mediables'))
            ->where('media_id', $media->id)
            ->get(['mediable_type', 'mediable_id']);

        if ($rows->isEmpty()) {
            // Orphaned media (no mediable parent) — deny for portal users.
            return false;
        }

        foreach ($rows as $row) {
            if (! class_exists($row->mediable_type)) {
                continue;
            }

            try {
                $parent = app($row->mediable_type)::find($row->mediable_id);
            } catch (\Exception $e) {
                continue;
            }

            if (! $parent) {
                continue;
            }

            // If the parent model has a contact_id column, enforce ownership.
            if (in_array('contact_id', $parent->getFillable(), true) || isset($parent->contact_id)) {
                if (isset($parent->contact_id) && (int) $parent->contact_id === (int) $contact->id) {
                    return true;
                }
            }
        }

        // No matching owned parent found — default deny.
        return false;
    }
}
