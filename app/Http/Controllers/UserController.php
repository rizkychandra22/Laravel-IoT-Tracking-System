<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function indexUser()
    {
        return view('home.view_user.index');
    }
}
