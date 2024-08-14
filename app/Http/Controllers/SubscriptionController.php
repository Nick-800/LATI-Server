<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Models\ServerUser;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{

    public function store($code)
    {
        $server = Server::where('code', '=', $code)
            ->firstOrFail();

        $check = auth()->user()->subscriptions()->where('code', '=', $code)
            ->exists();
        if ($check) {
            return response()->json([
                'data' => 'you are already joined the server'
            ], 422);
        }
        $server->subscribers()->attach(auth()->id());

        return response()->json([
            'data' => 'joined successfully'
        ]);
    }

    public function destroy($code)
    {
        $server = auth()->user()->subscriptions()
            ->where('code', '=', $code)
            ->firstOrFail();
        auth()->user()->subscriptions()->detach($server->id);
        return response()->json([
            'data' => 'you left the server'
        ]);
    }

    public function index($code)
    {
        $server = auth()->user()->subscriptions()
            ->where('code', '=', $code)
            ->firstOrFail();

        $users = $server->subscribers;

        return response()->json([
            'data' => $users
        ]);

    }
}
