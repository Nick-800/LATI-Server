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
<<<<<<< HEAD
=======
    public function search(Request $request,$code){
        $server=Server::where('code','=',$code)->firstOrFail();

        $subscribers=$server->subscribers()->where('user_id','=', auth()->id())->firstOrFail();
        
        $search= $request->search ?? null;
        if ($search==null) {
            $users=$subscribers->get();
            return response()->json(['data'=>$users]);
        }
        if($search[0]=="0"){
            $users=$subscribers->whereLike('phone',''.$search.'%',)->get();
        return response()->json(['data'=>$users]);
        }
        else{
            $users=$subscribers->where('user_id','=',$search)->firstOrFail();
            return response()->json(['data'=>$users]);
        }
        
    }
>>>>>>> e37be1f8ea21ddee2eacc70d4f9a735e6f2fa1e4
}
