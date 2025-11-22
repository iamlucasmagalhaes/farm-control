<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/gado' => [[['_route' => 'cow_index', '_controller' => 'App\\Controller\\CowController::index'], null, null, null, false, false, null]],
        '/gado/adicionar' => [[['_route' => 'cow_add', '_controller' => 'App\\Controller\\CowController::addCow'], null, null, null, false, false, null]],
        '/fazenda' => [[['_route' => 'farm_index', '_controller' => 'App\\Controller\\FarmController::index'], null, null, null, false, false, null]],
        '/fazenda/adicionar' => [[['_route' => 'farm_add', '_controller' => 'App\\Controller\\FarmController::addFarm'], null, null, null, false, false, null]],
        '/veterinario' => [[['_route' => 'veterinarian_index', '_controller' => 'App\\Controller\\VeterinarianController::index'], null, null, null, false, false, null]],
        '/veterinario/adicionar' => [[['_route' => 'veterinarian_add', '_controller' => 'App\\Controller\\VeterinarianController::addVeterinarian'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/gado/editar/([^/]++)(*:63)'
                .'|/fazenda/(?'
                    .'|editar/([^/]++)(*:97)'
                    .'|apagar/([^/]++)(*:119)'
                .')'
                .'|/veterinario/(?'
                    .'|editar/([^/]++)(*:159)'
                    .'|apagar/([^/]++)(*:182)'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        63 => [[['_route' => 'cow_edit', '_controller' => 'App\\Controller\\CowController::editCow'], ['id'], null, null, false, true, null]],
        97 => [[['_route' => 'farm_edit', '_controller' => 'App\\Controller\\FarmController::editFarm'], ['id'], null, null, false, true, null]],
        119 => [[['_route' => 'farm_remove', '_controller' => 'App\\Controller\\FarmController::removeFarm'], ['id'], null, null, false, true, null]],
        159 => [[['_route' => 'veterinarian_edit', '_controller' => 'App\\Controller\\VeterinarianController::editVeterinarian'], ['id'], null, null, false, true, null]],
        182 => [
            [['_route' => 'veterinarian_remove', '_controller' => 'App\\Controller\\VeterinarianController::removeVeterinarian'], ['id'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
