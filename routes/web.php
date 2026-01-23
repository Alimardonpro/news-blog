<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/profile', function () {
    return view('profile');
});

Route::get('/followers', function () {
    return view('followers');
});

Route::get('/chat', function () {
    return view('chat');
});

Route::get('/notification', function () {
    return view('notification');
});

?>



