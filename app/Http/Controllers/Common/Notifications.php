<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Common\Notification as Request;
use App\Traits\Modules;
use App\Utilities\Date;
use Illuminate\Support\Str;

class Notifications extends Controller
{
    use Modules;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('common.notifications.index');
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function readAll()
    {
        $notifications = user()->unreadNotifications;

        foreach ($notifications as $notification) {
            $notification->markAsRead();
        }

        // Hide New Apps Notifications
        $module_notifications = $this->getNotifications('new-apps');

        foreach ($module_notifications as $module_notification) {
            $prefix = 'notifications.' . user()->id . '.' . $module_notification->alias;

            setting()->set([
                $prefix . '.name'       => $module_notification->name,
                $prefix . '.message'    => $module_notification->alias,
                $prefix . '.date'       => Date::now(),
                $prefix . '.status'     => '0',
            ]);
        }

        setting()->save();

        $message = trans('messages.success.clear_all', ['type' => Str::lower(trans_choice('general.notifications', 2))]);

        flash($message)->success();

        return redirect()->route('dashboard');
    }

    /**
     * Disable the specified resource.
     *
     * @param  Company  $company
     *
     * @return Response
     */
    public function disable(Request $request)
    {
        $id = $request['id'];
        $path = str_replace('#', '/', $request['path']);

        $notifications = $this->getNotifications($path);

        foreach ($notifications as $notification) {
            if ($notification->id == $id) {
                $prefix = 'notifications.' . $path . '.' . $id;

                setting()->set([
                    $prefix . '.name'       => $notification->name,
                    $prefix . '.message'    => $notification->message,
                    $prefix . '.date'       => Date::now(),
                    $prefix . '.status'     => '0',
                ]);

                setting()->save();

                break;
            }
        }

        return response()->json([
            'message' => trans('messages.success.disabled', [
                'type' => Str::lower(trans_choice('general.notifications', 2))
            ]),
            'success' => true,
            'error' => false,
            'data' => null,
        ]);
    }
}
