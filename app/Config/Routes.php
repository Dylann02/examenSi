<?php

use CodeIgniter\Router\RouteCollection;
// use App\Controllers\ProduitController;
/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/produits','ProduitController::getProduit');
$routes->get('/produit/(:num)' ,'ProduitController::getProduitById/$1');