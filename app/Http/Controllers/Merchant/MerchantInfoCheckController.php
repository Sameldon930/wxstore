<?php

namespace App\Http\Controllers\Merchant;

use App\AccountLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MerchantInfoCheckController extends Controller
{
    public function index(){
        return _view('merchant.merchant.info_check', compact('data'));
    }
}
