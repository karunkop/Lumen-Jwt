<?php

    $router->group(['middleware'=>'auth'], function ()use($router){
        $router->post('/messages','MessageController@addMessage');
        $router->delete('/messages/{id}','MessageController@deleteMessage');

        $router->get('/messages/{group_id}','MessageController@getMessages');
    });
