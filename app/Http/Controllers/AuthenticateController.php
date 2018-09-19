<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthenticateController extends Controller
{
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials,['type' => 'access'])) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        try {
            $user = User::where('email',$credentials['email'])->get()->first();
            $tokens = $this->tokens($user->id);
            return response()->json(compact('tokens'));
        }
        catch (\Exception $e) {
            report($e);
            return response()->json('Please Contact Our Support Time', 500);
        }
    }

    public function freshTokens()
    {
        try{
            $payload = JWTAuth::parseToken()->getPayload();
        } catch (JWTException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        if (!(isset($payload["type"]) && $payload["type"] == "refresh"))
        {
            return response()->json(['error' => 'refresh_token_invalid'], 401);
        }
        $tokens = $this->tokens($payload['sub']);
        return response()->json($tokens,200);
    }

    private function tokens($id){
        $tokens = [];
        $access_token_exp = time() + 2 * 60 * 60;//2 hours
        $refresh_token_exp = time() + 14 * 24 * 60 * 60;//2 weeks
        $access_token_custom_claims = ['exp' => $access_token_exp, 'sub' => $id, 'type' => 'access'];
        $refresh_token_custom_claims = ['exp' => $refresh_token_exp, 'sub' => $id, 'type' => 'refresh'];
        $access_payload = JWTFactory::make($access_token_custom_claims);
        $refresh_payload = JWTFactory::make($refresh_token_custom_claims);
        $tokens['token'] = (string)JWTAuth::encode($access_payload);
        $tokens['refreshToken'] = (string)JWTAuth::encode($refresh_payload);
        return $tokens;
    }
}

