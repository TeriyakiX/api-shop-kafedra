<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Ошибка валидации',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();

        return response()->json(['message' => 'Регистрация прошла успешна']);

    }
    public function login(Request $request)
    {
        $validator = Validator($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Ошибка валидации',
                'errors' => $validator->errors()
            ], 422);
        }

        $creds = $request->only(['email', 'password']);

        if (Auth::attempt($creds)) {
            $user = Auth::user();
            $token = Str::random(60);

            if ($user !== null) {
                $user->api_token = $token;
                $user->save();

                return response()->json(['token' => $token], 200);
            }
        }

        return response()->json(['message' => 'Такого пользователя не существует'], 404);
    }

    public function logout(Request $request)
    {
        if (!auth()->user()) {
            return response()->json([
                'message' => 'Login failed'
            ], 403);
        }

        $user = $request->user();
        $user->api_token = null;
        $user->save();
        return response()->json(['message' => 'Вы успешно вышли'], 200);
    }
}
