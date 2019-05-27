<?php

$router->post('/login', 'UserController@login');
$router->post('/signup', 'UserController@signup');
$router->get('/users',['middleware'=> 'auth', 'uses'=> 'UserController@list']);
$router->get('/home', ['middleware' => 'auth', 'uses' => 'UserController@home']);
$router->get('/users/events', ['middleware' => 'auth', 'uses' => 'UserController@events']);

$router->get('/users/{id}/{event_id}/attach', 'UserController@attachEvent');
$router->get('/users/{id}/{event_id}/detach', 'UserController@detachEvent');
