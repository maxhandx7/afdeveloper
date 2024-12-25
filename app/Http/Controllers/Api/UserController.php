<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;

class UserController extends Controller
{
    public function register(Request $request)
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        $data = ["success" => false, "menssage" => "no se puede registrar"];
        User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

       

     
        return $this->login($request);
    }


    public function login(Request $request)
    {
        $data = ["success" => false, "menssage" => "no se puede logear"]; 
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);
          
        $user = User::whereEmail($request->email)->first();

        if (!empty($user)) {
            $data = ["success" => false, "menssage" => "no se puede logear"]; 
            if (Hash::check($request->password, $user->password)) {
                $accessToken = $user->createToken("auth_token")->plainTextToken;
                $data = ["success" => true, 
                "menssage" => "usuario logeado",
                "user_id" => $user->id,
                "access_token" => $accessToken,
            ];  
            }
        }
        return response()->json($data, 200, );
    }


    public function logout()
    {
        auth()->user()->tokens()->delete();
        $data = ["success" => true, 
                "menssage" => "sesion finalizada",
            ];  
        return response()->json($data, 200, );  
    }


}
