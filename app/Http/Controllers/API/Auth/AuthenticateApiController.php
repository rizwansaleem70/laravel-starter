<?php 

namespace App\Http\Controllers\API\Auth;

use App\User;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\API\LoginApiRequest;
use App\Http\Requests\API\RegisterApiRequest;
use App\Notifications\UserRegisterNotification;

class AuthenticateApiController extends Controller
{
    public function login(LoginApiRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->sendJson(false, 'Invalid Credentials');
        }

        $user->tokens()->delete();

        return $this->sendJson(true, "Success", [
            'token' => $user->createToken(Str::random(10))->plainTextToken,
            'user' => $user
        ]);
    }


    public function register(RegisterApiRequest $request)
    {
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'name' => $request->name
        ]);

        $user->notify(new UserRegisterNotification());

        return $this->sendJson(true, "Success", [
            'user' => $user
        ]);
    }
}
