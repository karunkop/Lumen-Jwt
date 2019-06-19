<?php
    $router->group(['middleware'=>'auth'], function ()use($router){
        $router->post('/groups/user','GroupController@attachUser');
        $router->delete('/groups/user','GroupController@detachUser');
        $router->get('/groups/of/{event_id}','GroupController@getGroupOfEvent');
        $router->get('/groups/{id}','GroupController@getGroup');
        $router->post('/groups',['as'=>'addGroup','uses'=>'GroupController@addGroup']);
        $router->delete('/groups/{id}','GroupController@deleteGroup');
    });
