<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Historic;

class AuthController extends Controller
{
    public function register (StoreUserRequest $request)
    {
        $user = $request->validated();
        $user['password'] = Hash::make($user['password']);
        $user = User::create($user);
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'utilisateur créé avec succès',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        $identifiants = $request->only('email', 'password');

        if (!Auth::attempt($identifiants)) {
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }
        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        //Enregistrement de l'historique de connexion
        Historic::create([
            'user_id' => $user->id,
            'activite' => 'login',
            'dateConnexion' => now()
        ]);


        return response()->json([
            'message' => 'connexion réussie',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        //$request->user()->currentAccessToken()->delete();
        optional($request->user()->currentAccessToken())->delete();
        // Enregistrement de l'historique de déconnexion
        Historic::create([
            'user_id' => $request->user()->id,
            'activite' => 'logout',
            'heureDeconnexion' => now()
        ]);

        return response()->json(['message' => 'deconnexion réussie'], 200);
    }
}
