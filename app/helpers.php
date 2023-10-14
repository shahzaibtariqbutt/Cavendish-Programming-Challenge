<?php

use Illuminate\Support\Facades\Auth;

if (! function_exists('user')) {
    function user() {
        $user = Auth::user();
        if($user){
            return $user;
        }else{
            return null;
        }
    }
}