<?php

namespace App\Http\Controllers;
use App\Models\user;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request){
        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials)){
            return redirect()->route('dashboard.index');
        }else{
            $msg = new MessageBag();
            $msg->add('error', 'Usuario e/ou Senha invalido');
            return redirect()->back()->withErrors($msg);
        }

    }

    public function register(Request $request){
        $request->validate([
            'name' => ['bail', 'required', 'regex:/(^[A-Za-z ]+$)+/', 'max:50'],
            'email' => ['bail', 'required', 'email', 'max:50', 'unique:App\Models\User,email'],
            'password' => ['min:5', 'max:20', 'regex:/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/'],
            'confirm_password' => ['bail', 'required', 'same:password'],
        ], [
            'name.required' => 'Formato de nome inválido!',
            'name.regex' => 'Formato de nome inválido!',
            'name.max' => 'Formato de nome muito longo!',
            'email.required' => 'Formato de email inválido!',
            'email.email' => 'Formato de email inválido!',
            'email.max' => 'Email muito longo!',
            'email.unique' => 'Este email já está cadastrado!',
            'password.min' => 'Senha muito curta!',
            'password.max' => 'Senha muito longa!',
            'password.regex' => 'Senha senha precisa conter letras e numeros!',
            'confirm_password.required' => 'Você precisa confirmar sua senha!',
            'confirm_password.same' => 'As senha não são iguais!',
        ]);


        $newUser = new user();

        $newUser->name = $request->name;
        $newUser->email = $request->email;
        $nwePassword = Hash::make($request->password, [
            'rounds' => 12,
        ]);
        $newUser->password = $nwePassword;
        
        $newUser->save();

        return redirect()->route('user.login.index');
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('user.login');
    }
}
