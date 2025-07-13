<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function signup(Request $request){
        
        $request->validate([
            'name' => 'required',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required|min:5|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('api', ['post:read', 'post:create'])->plainTextToken;
        return response()->json(['user' => $user, 'token' => $token]);

    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required|min:5'
        ]);

        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials)){

            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('api', ['post:read', 'post:create'])->plainTextToken;
            return response()->json(['user' => $user, 'token' => $token]);

        }

        return response()->json(['error' => 'Usuário não encontrado']);

    }

    public function logout(Request $request){
        $token = $request->bearerToken();

        if($token){
            $access_token = PersonalAccessToken::findToken($token);
            if($access_token){
                $access_token->delete();
                return response()->json("Logout Feito com Sucesso!");
            }
            return response()->json("Token informado inválido");
        }
        
        return response()->json("Enviar um token de acesso");
    }

    public function NomeEdit(Request $request){
        $request->validate([
            'name' => 'required',
        ]);

        $user = $request->user();
        $user->name = $request->name;
        $user->save();

        return response()->json('Nome atualizado!');

    }
}
