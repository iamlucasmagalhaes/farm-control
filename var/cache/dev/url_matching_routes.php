<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/gado' => [[['_route' => 'gado_index', '_controller' => 'App\\Controller\\CowController::index'], null, null, null, false, false, null]],
        '/gado/adicionar' => [[['_route' => 'gado_add', '_controller' => 'App\\Controller\\CowController::addCow'], null, null, null, false, false, null]],
        '/fazenda' => [[['_route' => 'farm_index', '_controller' => 'App\\Controller\\FarmController::index'], null, null, null, false, false, null]],
        '/fazenda/adicionar' => [[['_route' => 'farm_add', '_controller' => 'App\\Controller\\FarmController::addFarm'], null, null, null, false, false, null]],
        '/veterinario' => [[['_route' => 'veterinarian_index', '_controller' => 'App\\Controller\\VeterinarianController::index'], null, null, null, false, false, null]],
        '/veterinario/adicionar' => [[['_route' => 'veterinarian_add', '_controller' => 'App\\Controller\\VeterinarianController::addVeterinarian'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [
            [['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
