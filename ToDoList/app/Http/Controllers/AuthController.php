<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Validação
use Illuminate\Support\Facades\Validator;

// Autenticação e Hash
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// Model
use App\Models\User;

class AuthController extends Controller
{
    public function create(Request $request)
    {
        $array = ['error' => ''];

        // Validando
        $rules = [
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ];

        // Validation
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $array['error'] = $validator->errors();

            return $array;
        }

        // Recebendo os dados do input
        $email = $request->input('email');
        $password = $request->input('password');

        // Criando novo usuario
        $newUser = new User();
        $newUser->email = $email;
        $newUser->password = password_hash($password, PASSWORD_DEFAULT);
        $newUser->token = '';
        $newUser->save();

        // Logar o usuario recém criado

        return $array;
    }

    // Função de Login sem JWT
    /*  public function login(Request $request)
    {
        $array = ['error' => ''];

        $credenciais = $request->only('email', 'password');

        // Fazendo autenticação
        if (Auth::attempt($credenciais)) {
            $user = User::where('email', $credenciais['email'])->first();

            $hash = time() . rand(0, 9999);
            $token = $user->createToken($hash)->plainTextToken;

            $array['token'] = $token;
        } else {
            $array['error'] = 'E-mail e/ou senha incorretos';
        }
        return $array;
    } 
    
      // Logout sem JWT
    public function logout(Request $request)
    {
        $array = ['error' => ''];

        $user = $request->user();
        $user->tokens()->delete();

        return $array;
    }
    
    */

    // Função de Login com JWT
    public function login(Request $request)
    {
        $array = ['error' => ''];

        $credenciais = $request->only('email', 'password');

        $token = Auth::attempt($credenciais);

        // Fazendo autenticação
        if ($token) {
            $array['token'] = $token;
        } else {
            $array['error'] = 'E-mail e/ou senha incorretos';
        }
        return $array;
    }

    // Logout com JWT
    public function logout()
    {
        $array = ['error' => ''];

        Auth::logout();

        return $array;
    }

    // Pegar o usuario logado
    public function me()
    {
        $array = ['error' => ''];

        $user = Auth::user();

        $array['email'] = $user->email;

        return $array;
    }
}
