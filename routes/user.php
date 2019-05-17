<?php

$router->post('/login', 'UserController@login');
$router->post('/signup', 'UserController@signup');
$router->get('/home', ['middleware' => 'auth', 'uses' => 'UserController@home']);
