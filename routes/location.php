<?php

    $router->get('/locations','LocationController@getLocations');
    $router->get('/locations/{id}','LocationController@getUserLocation');
    $router->post('/locations/{id}','LocationController@addUserLocation');

    $router->get('/locations/nearby/{id}','LocationController@getNearbyUsers');
