<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TokenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'expo_push_token' => 'required|string',
        ]);

        $userId = $request->input('user_id');
        $token = $request->input('expo_push_token');

        $supabaseUrl = rtrim(env('SUPABASE_URL'), '/');
        $supabaseKey = env('SUPABASE_KEY');

        try {
            $response = Http::withHeaders([
                'apikey' => $supabaseKey,
                'Authorization' => 'Bearer ' . $supabaseKey,
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation',
            ])->patch($supabaseUrl . "/rest/v1/users?id=eq.{$userId}", [
                'expo_push_token' => $token,
            ]);

            if ($response->failed()) {
                Log::error('Supabase token update failed', ['status' => $response->status(), 'body' => $response->body()]);
                return response()->json(['error' => 'Failed to update token in Supabase'], 500);
            }

            return response()->json(['success' => true, 'data' => $response->json()]);

        } catch (\Exception $e) {
            Log::error('Supabase token update exception', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while updating the token'], 500);
        }
    }
}
