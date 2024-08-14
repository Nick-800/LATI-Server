<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function store(Request $request)
    {
        $inputs = $request->validate([
            'name' => ['required', 'string'],
            'description' => ['max:1024'],
            'before_date' => ['date'],
            'priority' => ['required', 'integer', 'min:1', 'max:5'],
            'status' => ['string', 'in:Not-Started,In-Progress,Completed,Cancelled']
        ]);
        auth()->user()->servers()->firstOrFail()->tasks()->create($inputs);

//        $server = Server::where('user_id', '=', $userID)
//            ->firstOrFail();
//
//        $serverId = $server->id;
//
//        Task::create([
//            'status' => $inputs['status'] ?? null,
//            'priority' => $inputs['priority'],
//            'before_date' => $inputs['before_date'] ?? null,
//            'description' => $inputs['description'] ?? null,
//            'name' => $inputs['name'],
//            'server_id' => $serverId
//        ]);

        return response()->json([
            'data' => 'task created successfully'
        ]);
    }
}
