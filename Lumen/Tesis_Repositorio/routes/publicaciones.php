<?php 

    $router->group(['middleware' => 'auth'], function() use ($router){
        $router->group(['prefix' => 'api/v1','namespace' => 'Publicaciones'], function () use ($router){
            $router->get('/publicaciones', 'PublicacionesController@getAllApproved');
            $router->get('/publicaciones/{id}', 'PublicacionesController@getById');
            $router->post('/publicaciones', 'PublicacionesController@store');
            #$router->put('/user', 'UsuarioController@update');
            $router->get('/publicaciones/{id}/delete', 'PublicacionesController@delete');
            #$router->get('/user/{id}/activate', 'UsuarioController@activate');
        });
    });