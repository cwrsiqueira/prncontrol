<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class AjaxController extends Controller
{
    public function getUser(Request $request)
    {
        $user = User::find($request->id);
        return $user;
    }
}
