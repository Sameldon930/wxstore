<?php

namespace App\Http\Controllers\Merchant;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(){

        $data = User::getMerchantAuthUser();

        return _view('merchant.profile.index',compact('data'));
    }
}

