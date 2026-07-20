<?php

use CodeIgniter\Router\RouteCollection;
// use App\Controllers\ProduitController;
/**
 * @var RouteCollection $routes
 */
$routes->group('operateur', function ($routes) {
    // Gains & Suivi
    $routes->get('gains', 'OperateurController::gains');
    $routes->get('clients', 'OperateurController::suiviClients');
    $routes->get('clients/historique/(:num)', 'OperateurController::historiqueClient/$1');

    // CRUD Préfixes
  $routes->get('prefixes', 'PrefixeController::index');
    $routes->post('prefixes/store', 'PrefixeController::store');
    $routes->get('prefixes/edit/(:num)', 'PrefixeController::edit/$1'); 
    $routes->post('prefixes/update/(:num)', 'PrefixeController::update/$1');
    $routes->get('prefixes/delete/(:num)', 'PrefixeController::delete/$1');

    // CRUD Barèmes
    $routes->get('baremes', 'BaremeController::index');
    $routes->post('baremes/store', 'BaremeController::store');
    $routes->get('baremes/edit/(:num)', 'BaremeController::edit/$1');
    $routes->post('baremes/update/(:num)', 'BaremeController::update/$1');
    $routes->get('baremes/delete/(:num)', 'BaremeController::delete/$1');
});