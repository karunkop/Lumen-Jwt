<?php

$router->post('/login', 'UserController@login');
$router->post('/signup', 'UserController@signup');

/**
 * "username"=> 'required|unique:users',
  *          "bio"=>'required',
  *          "f_name"=> 'required',
  *          "l_name"=> 'required',
  *          "email"=> 'required|email|unique:users',
  *          "phone_no"=>'required',
  *          "address"=>'required'
 */
$router->post('/users/{id}/update', ['middleware'=> 'auth', 'uses'=> 'UserController@update']);
$router->put('/users/permission',['middleware'=> 'auth', 'uses'=> 'UserController@changePermission']);
$router->get('/users',['middleware'=> 'auth', 'uses'=> 'UserController@list']);
$router->get('/home', ['middleware' => 'auth', 'uses' => 'UserController@home']);
$router->get('/users/events', ['middleware' => 'auth', 'uses' => 'UserController@events']);

$router->get('/users/{id}/{event_id}/attach', 'UserController@attachEvent');
$router->get('/users/{id}/{event_id}/detach', 'UserController@detachEvent');
