<?php

use CodeIgniter\Router\RouteCollection;
// use App\Controllers\ProduitController;
/**
 * @var RouteCollection $routes
 */
// Routes pour l'authentification et l'espace Client


$routes->group('client', function ($routes) {
    $routes->get('login', 'ClientController::login');
    $routes->post('login', 'ClientController::handleLogin');
    $routes->get('logout', 'ClientController::logout');
    $routes->get('dashboard', 'ClientController::dashboard');
    $routes->post('action', 'ClientController::executerAction');
});
// Groupe Espace Admin pour la gestion des operateurs
$routes->group('operateur', function ($routes) {
    $routes->post('handleLogin', 'OperateurController::handleLogin');
    $routes->get('dashboard', 'OperateurController::dashboard');
});