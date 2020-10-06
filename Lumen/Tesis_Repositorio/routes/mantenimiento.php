<?php 

    $router->group(['middleware' => 'auth'], function() use ($router){
        $router->group(['prefix' => 'api/v1','namespace' => 'Mantenimiento'], function () use ($router){
            # PARAMETROS
            $router->get('/parametros', 'ParametrosController@getAll');
            $router->get('/parametros/inactive', 'ParametrosController@getAllInactive');
            $router->get('/parametros/{id}', 'ParametrosController@getById');
            $router->get('/parametros/code/{code}', 'ParametrosController@getByCode');
            $router->post('/parametros', 'ParametrosController@store');
            $router->put('/parametros', 'ParametrosController@update');
            $router->get('/parametros/{id}/delete', 'ParametrosController@delete');
            # FILTROS ( TIPO DISCAPACIDAD )
            $router->get('/tipodiscapacidad', 'TipoDiscapacidadController@getAll');
            $router->get('/tipodiscapacidad/{id}', 'TipoDiscapacidadController@getById');
            $router->post('/tipodiscapacidad', 'TipoDiscapacidadController@store');
            $router->put('/tipodiscapacidad', 'TipoDiscapacidadController@update');
            $router->get('/tipodiscapacidad/{id}/delete', 'TipoDiscapacidadController@delete');
            # FILTROS ( CLASIFICACION DISCAPACIDAD )
            $router->get('/clasificaciondiscapacidad', 'ClasificacionDiscapacidadController@getAll');
            $router->get('/clasificaciondiscapacidad/{id}', 'ClasificacionDiscapacidadController@getById');
            $router->get('/clasificaciondiscapacidad/tipodiscapacidad/{idTipo}', 'ClasificacionDiscapacidadController@getByTipoDiscapacidad');
            $router->post('/clasificaciondiscapacidad', 'ClasificacionDiscapacidadController@store');
            $router->put('/clasificaciondiscapacidad', 'ClasificacionDiscapacidadController@update');
            $router->get('/clasificaciondiscapacidad/{id}/delete', 'ClasificacionDiscapacidadController@delete');
            # FILTROS ( DISCAPACIDAD )
            $router->get('/discapacidad', 'DiscapacidadController@getAll');
            $router->get('/discapacidad/{id}', 'DiscapacidadController@getById');
            $router->get('/discapacidad/clasificaciondiscapacidad/{idClasificacion}', 'DiscapacidadController@getByClasificacionDiscapacidad');
            $router->post('/discapacidad', 'DiscapacidadController@store');
            $router->put('/discapacidad', 'DiscapacidadController@update');
            $router->get('/discapacidad/{id}/delete', 'DiscapacidadController@delete');

        });
    });