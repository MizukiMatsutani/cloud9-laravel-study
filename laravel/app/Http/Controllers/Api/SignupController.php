<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Eloquents\Friend;
use App\Http\Requests\Api\SignupRequest;
use App\Http\Resources\AccountResource;

class SignupController extends Controller
{
    protected $friend;
    
    public function __construct(Friend $friend)
    {
        $this->friend = $friend;
    }
    
    public function signup(SignupRequest $request)
    {
        $myInfo = $this->friend->store(
            $request->input('email'),
            $request->input('password'),
            $request->input('nickname')
        );

        return new AccountResource($myInfo);
    }
}
