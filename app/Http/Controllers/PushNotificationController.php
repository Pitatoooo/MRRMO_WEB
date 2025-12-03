<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PushNotificationController extends Controller
{
    public function send(Request $request)
    {
        try {
            $userId = $request->input('user_id');
            $reportId = $request->input('report_id');

            if (!$userId) {
                return response()->json(['success' => false, 'error' => 'user_id missing']);
            }

            // Fetch user's expo_push_token from Supabase REST API
            $supabaseUrl = env('SUPABASE_URL') . '/rest/v1/users';
            $supabaseKey = env('SUPABASE_KEY');

            $response = Http::withHeaders([
                'apikey' => $supabaseKey,
                'Authorization' => "Bearer $supabaseKey",
                'Accept' => 'application/json'
            ])->get($supabaseUrl, [
                'id' => "eq.$userId",
                'select' => 'expo_push_token'
            ]);

            $data = $response->json();

            if (empty($data) || !isset($data[0]['expo_push_token'])) {
                return response()->json(['success' => false, 'error' => 'No push token found for this user']);
            }

            $token = $data[0]['expo_push_token'];

            // Send push via Expo API
            $pushResponse = Http::post(env('EXPO_PUSH_API'), [
                "to" => $token,
                "title" => "Help is on the way!",
                "body" => "Your emergency report #$reportId has been accepted.",
                "sound" => "default",
                "data" => ["report_id" => $reportId]
            ]);

            return response()->json(['success' => true, 'expo' => $pushResponse->json()]);

        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
