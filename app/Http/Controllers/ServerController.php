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
        $servers = Server::where('user_id', '=', auth()->id())->get();
        return response()->json([
            'data' => $servers
        ]);
    }

    public function show($code)
    {
        $server = Server::where('user_id', auth()->id())
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
        $server = Server::where('user_id', auth()->id())->where('id', $id)->firstOrFail();
        $server->update($inputs);
        return response()->json([
            'message' => 'server updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $server = Server::where('user_id', auth()->id())
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
}
