<?php

namespace App\Http\Controllers\Stores;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(){

        $user_id = Auth::guard('stores')->id();
        $data = User::where('id',$user_id)->first();

        return _view('stores.profile.index',compact('data'));
    }
}
