<?php

namespace App\Http\Controllers\API\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\ResetPasswordRequest;
use App\Http\Requests\API\ForgetPasswordRequest;
use App\Notifications\ForgetPasswordNotification;

class ForgetPasswordApiController extends Controller
{
    public function forgotPassword(ForgetPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        $user->password_reset_code = rand(11111,99999);
        $user->save();
        $user->notify(new ForgetPasswordNotification());
        return $this->sendJson(true, "Reset code sent to your email");

    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::where('password_reset_code', $request->password_reset_code)->where('email', $request->email)->first();
        if($user)
        {
            $user->password = bcrypt($request->new_password);
            $user->password_reset_code = null;
            $user->save();
            return $this->sendJson(true, 'Password updated');
        }else{
            return $this->sendJson(false, 'Invalid Code Or Email');
        }
        
    }
}
