<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PushController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $userId = $request->input('user_id');

        // 1) Fetch token from Supabase users table via REST
        $supabaseUrl = rtrim(env('SUPABASE_URL'), '/');
        $supabaseKey = env('SUPABASE_KEY');

        try {
            // Query users table for expo_push_token
            $resp = Http::withHeaders([
                'apikey' => $supabaseKey,
                'Authorization' => 'Bearer ' . $supabaseKey,
                'Content-Type' => 'application/json',
            ])->get($supabaseUrl . "/rest/v1/users", [
                'select' => 'expo_push_token',
                'id' => 'eq.' . $userId
            ]);

            if ($resp->failed()) {
                Log::error('Supabase request failed', ['status' => $resp->status(), 'body' => $resp->body()]);
                return response()->json(['error' => 'Failed to query Supabase'], 500);
            }

            $data = $resp->json();
            if (empty($data) || empty($data[0]['expo_push_token'])) {
                return response()->json(['error' => 'No push token found for user'], 404);
            }

            $token = $data[0]['expo_push_token'];
        } catch (\Exception $e) {
            Log::error('Supabase fetch exception', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Supabase fetch error'], 500);
        }

        // 2) Send push via Expo
        $expoPayload = [
            'to' => $token,
            'title' => $request->input('title', 'Emergency Update'),
            'body' => $request->input('body', 'Your report has been accepted.'),
            'data' => $request->input('data', ['report_id' => $request->input('report_id')]),
            'sound' => $request->input('sound', 'default'),
        ];

        try {
            $sendResp = Http::post(env('EXPO_PUSH_API'), $expoPayload);

            if ($sendResp->failed()) {
                Log::error('Expo push failed', ['status' => $sendResp->status(), 'body' => $sendResp->body()]);
                return response()->json(['error' => 'Expo push failed'], 500);
            }

            return response()->json(['sent' => true, 'expo_response' => $sendResp->json()]);
        } catch (\Exception $e) {
            Log::error('Expo send exception', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Expo send error'], 500);
        }
    }
}
