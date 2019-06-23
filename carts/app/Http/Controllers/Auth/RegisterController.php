<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\RegisterFormRequest;
use App\Http\Resources\PrivateUserResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    //
    public function action(RegisterFormRequest $request)
    {
        $user = User::create($request->only('email', 'password', 'name'));

        return new PrivateUserResource($user);
    }
}
