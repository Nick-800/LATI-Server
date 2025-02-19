<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServerController extends Controller
{

    public function store(Request $request)
    {
        $inputs = $request->validate([
            'name' => ['required', 'string'],
        ]);
        if (Server::where('user_id', '=', auth("api")->id())->exists()) {
            return response()->json([
                'data' => 'you can not create more then one server'
            ], 422);
        }
        $code = $this->generateCode();
        auth("api")->user()->servers()->create([
            'code' => $code,
            'name' => $inputs['name']
        ]);
        return response()->json([
            'message' => 'server created successfully',
            'code' => $code
        ]);


    }

    public function index()
    {
        $servers = Server::where('user_id', '=', auth("api")->id())->get();
        return response()->json([
            'data' => $servers
        ]);
    }

    public function show($code)
    {
        $server = Server::where('user_id', auth("api")->id())
            ->where('code', $code)
            ->firstOrFail();
        return response()->json([
            'data' => $server
        ]);
    }


    public function update(Request $request, $id)
    {
        $inputs = $request->validate([
            'name' => ['required', 'string'],
        ]);
        $server = Server::where('user_id', auth("api")->id())->where('id', $id)->firstOrFail();
        $server->update($inputs);
        return response()->json([
            'message' => 'server updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $server = Server::where('user_id', auth("api")->id())
            ->where('id', $id)
            ->firstOrFail();
        $server->delete();
        return response()->json([
            'message' => 'server deleted successfully'
        ]);
    }


    private function generateCode(): string
    {
        $code = Str::random(6);
        if (Server::where('code', '=', $code)->exists()) {
            $this->generateCode();
        }
        return $code;
    }
    public function search(Request $request, $code)
{
    $server = Server::where('code', $code)->firstOrFail();
    $server->subscribers()->where('user_id', auth("api")->id())->firstOrFail();

    $subscribersQuery = $server->subscribers();

    $search = $request->search ?? null;
    if ($search == null) {
        $users = $subscribersQuery->get();
        return response()->json(['data' => $users]);
    }
    if ($search[0] == "0") {
        $users = $subscribersQuery->where('phone', 'like', $search. '%')->get();
        return response()->json(['data' => $users]);
    } else {
        $users = $subscribersQuery->where('user_id', '=', $search)->get();
        return response()->json(['data' => $users]);
    }
}
}
