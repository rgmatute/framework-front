<?php 

    $router->group(['middleware' => 'auth'], function() use ($router){
        $router->group(['prefix' => 'api/v1','namespace' => 'Mensajes'], function () use ($router){
            $router->get('/mensajes', 'MensajesController@getByFrom');
            $router->get('/mensajes/{to}', 'MensajesController@getByTo');
            $router->post('/mensajes', 'MensajesController@store');
            $router->get('/mensajes/{id}/delete', 'MensajesController@delete');
        });
    });