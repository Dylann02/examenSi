<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Groupe Opérateur
$routes->group('operateur', function ($routes) {
    
    // Page d'accueil du panel opérateur (tableau de bord)
    $routes->get('/', 'OperateurController::index'); 
    
    // Gains & Suivi
    $routes->get('gains', 'OperateurController::gains');
    $routes->get('clients', 'OperateurController::clients');
    $routes->get('clients/historique/(:num)', 'OperateurController::historiqueClient/$1');
    $routes->post('handleLogin', 'OperateurController::handleLogin');

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


$routes->group('client', function ($routes) {
    $routes->get('login', 'ClientController::login');
    $routes->post('login', 'ClientController::handleLogin');
    $routes->get('logout', 'ClientController::logout');
    $routes->get('dashboard', 'ClientController::dashboard');
    $routes->post('action', 'ClientController::executerAction');
});

// Redirection par défaut vers le login client
$routes->addRedirect('/', 'client/login');