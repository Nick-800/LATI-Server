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
        $code = $this->generateCode();
        auth()->user()->servers()->create([
            'code' => $code,
            'name' => $inputs['name']
        ]);
        return response()->json([
            'message' => 'server created successfully',
            'code' => $code
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
    public function index(){
        $servers=Server::where('user_id','=',auth()->id())->get();
        if($servers==null){
            return response()->json(['data'=>'the user doesn`t have any servers']) ;
        }
        return response()->json(['data'=>$servers]);
    }
    public function show($id){
        if(!Server::where('id','=', $id)->exists()){
            return response()->json(['data'=>' no such servers']);
        }
        $server=Server::where('user_id','=',auth()->id());
        if($server==null){
            return response()->json(['data'=>'user has no servers']);
        }
        $server=$server->find($id);
        if($server==null){
            return response()->json(['data'=> 'user not allowed']);
        }
        return response()->json(['data'=>$server]);
    }
}
