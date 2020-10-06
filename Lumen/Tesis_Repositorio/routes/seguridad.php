<?php

    $router->group(['prefix' => 'api/v1'], function () use ($router){
        $router->group(['namespace' => 'Seguridad'], function () use ($router){
            $router->post('/security/accounts/login', 'SeguridadController@login');
            $router->post('/security/accounts/register', 'SeguridadController@register');
        });
    });

    $router->group(['middleware' => 'auth'], function() use ($router){
        # ACTUALIZAR CONTRASEÑA
        $router->group(['prefix' => 'api/v1','namespace' => 'Seguridad'], function () use ($router){
            $router->get('/security/accounts/logout', 'SeguridadController@logout');
            $router->post('/security/accounts/update-password', 'SeguridadController@modificarCredencial');
        });
    });