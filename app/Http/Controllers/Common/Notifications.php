<?php

namespace App\Http\Controllers\Common;

use Date;
use App\Http\Controllers\Controller;
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
        $notifications = setting('notifications');

        return view('common.notifications.index', compact('notifications'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show($path, $id)
    {
        $notification = setting('notifications.' . $path . '.' . $id);

        return view('common.notifications.show', compact('notification'));
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
