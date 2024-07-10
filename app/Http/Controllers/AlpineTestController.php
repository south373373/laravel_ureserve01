<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlpineTestController extends Controller
{
    // 追記分
    public function index(){
        return view('alpine-test.index');
    }
}
