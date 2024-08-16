<?php

namespace App\Http\Controllers;

use App\Models\LinkedSocialAccount;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as ProviderUser;

class OAuth2Controller extends Controller
{
    public function login(Request $request)
    {
        dd($request);
        try {
            $accessToken = $request->get('access_token');
            $provider = $request->get('provider');
            $providerUser = Socialite::driver($provider)->userFromToken($accessToken);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ]);
        }

        if (filled($providerUser)) {
            $user = $this->findOrCreate($providerUser, $provider);
        } else {
            $user = $providerUser;
        }
        return response()->json([
            'message' => 'Logged in successfully',
            'data' => ['token' => $user->createToken($user->username)->plainTextToken],
        ]);
    }

    protected function findOrCreate(ProviderUser $providerUser, string $provider): User
    {
        $linkedSocialAccount = LinkedSocialAccount::query()->where('provider_name', $provider)
            ->where('provider_id', $providerUser->getId())
            ->first();

        if ($linkedSocialAccount) {
            return $linkedSocialAccount->user;
        } else {
            $user = null;

            if ($email = $providerUser->getEmail()) {
                $user = User::query()->where('email', $email)->first();
            }

            if (! $user) {
                $user = User::query()->create([
                    'username' => $providerUser->getName(),
                    'email' => $providerUser->getEmail(),
                ]);
                $user->markEmailAsVerified();
            }

            $user->linkedSocialAccounts()->create([
                'provider_id' => $providerUser->getId(),
                'provider_name' => $provider,
            ]);

            return $user;
        }
    }
}
