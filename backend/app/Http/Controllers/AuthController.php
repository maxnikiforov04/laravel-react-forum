<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @SWG\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     tags={"Register"},
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Invalid request")
     * )
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed:password_confirmation',
        ]);
        $user = User::create($data);
        $token = $user->createToken($request->email)->plainTextToken;
        return ["token" => $token, "user" => $user];
    }
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)){
            return response(['message' => 'Invalid Credentials']);
        }
        $token = $user->createToken($request->email)->plainTextToken;
        return ["token" => $token, "user" => $user];
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response(['message' => 'Logged out']);
    }

}
