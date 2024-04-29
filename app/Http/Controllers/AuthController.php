<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
// 6|IDCn7TDIgG2aw9c4O7DqaBqOe36Brb720EuoMyXMb359d0d0 -> invoice
// 7|f1pxiit3eKWENlDbRPS9OdRZzO6OEmoZcuSeGSvhba6d2327 -> user
// 8|LwYZYxKBU1VUZzkFogBoRn0qkkYzx1BNLgGq0iODdaa22327 -> teste

    use HttpResponses;

    public function login(Request $request){
        if(Auth::attempt($request->only("email","password"))){
            return $this->response('Authorized', 200,[
                'token'=> $request->user()->createToken('invoice')->plainTextToken,
        ]);
      }
      return $this->response('Not Authorized', 403);

    }
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return $this->response('Token Revoked',200);
    }
}
