<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class RegisterConfirmationController extends Controller
{
    public  function index()
    {
       //dd(request()->all());
        User::where('confirmation_token', request('token'))
              ->firstOrFail()
              ->confirm();
              //->update(['confirmed' => true]);
    }
}
