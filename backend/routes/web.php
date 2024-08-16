<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/social-login', function () {
    return redirect("http://127.0.0.1:300");
});
