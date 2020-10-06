<?php 

    $router->group(['middleware' => 'auth'], function() use ($router){
        $router->group(['prefix' => 'api/v1','namespace' => 'Usuarios'], function () use ($router){
            $router->get('/user', 'UsuarioController@getAll');
            $router->get('/user/{id}', 'UsuarioController@getById');
            $router->post('/user', 'UsuarioController@store');
            $router->put('/user', 'UsuarioController@update');
            $router->get('/user/{id}/delete', 'UsuarioController@delete');
            $router->get('/user/{id}/activate', 'UsuarioController@activate');
        });
    });