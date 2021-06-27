<?php

namespace App\Http\Controllers\Common;

use Date;
use App\Abstracts\Http\Controller;
use App\Traits\Modules as RemoteModules;
use App\Http\Requests\Common\Notification as Request;

class Notifications extends Controller
{
    use RemoteModules;

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
        $module_notifications = $this->getNotifications('new-apps' );

        foreach ($module_notifications as $module_notification) {
            setting()->set('notifications.'. user()->id . '.' . $module_notification->alias . '.name', $module_notification->name);
            setting()->set('notifications.'. user()->id . '.' . $module_notification->alias . '.message', $module_notification->alias);
            setting()->set('notifications.'. user()->id . '.' . $module_notification->alias . '.date', Date::now());
            setting()->set('notifications.'. user()->id . '.' . $module_notification->alias . '.status', '0');
        }

        setting()->save();

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.notificatinos', 1)]);

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
                setting()->set('notifications.'. $path . '.' . $id . '.name', $notification->name);
                setting()->set('notifications.'. $path . '.' . $id . '.message', $notification->message);
                setting()->set('notifications.'. $path . '.' . $id . '.date', Date::now());
                setting()->set('notifications.'. $path . '.' . $id . '.status', '0');

                setting()->save();
                break;
            }
        }

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => null,
        ]);
    }
}
