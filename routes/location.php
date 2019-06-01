<?php

        /*  pass in header:
            Authentication
        */
$router->group(['middleware'=>'auth'], function ()use($router){

    //$router->get('/locations/all', 'LocationController@getLocations');

    $router->get('/locations', 'LocationController@getUserLocation');
    /*pass in params:
        long,
        lat,
    */
    $router->post('/locations', 'LocationController@addUserLocation');

    /*pass in params:
        long,
        lat,
    */
    $router->get('/locations/nearby','LocationController@getNearbyUsers');

});
