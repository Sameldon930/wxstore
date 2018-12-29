<?php

namespace App\Http\Controllers\Agent;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(){

        $data = User::getAgentAuthUser();

        return _view('agent.profile.index',compact('data'));
    }
}

