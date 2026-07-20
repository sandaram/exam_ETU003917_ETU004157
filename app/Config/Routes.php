<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Route par défaut (Redirection vers le login client)
$routes->get('/', 'Client\AuthController::login');

/*
 ====================================================================
 📱 ROUTE GROUPE : CLIENT
 ====================================================================
 */
$routes->group('client', ['namespace' => 'App\Controllers\Client'], static function ($routes) {
    
    // 1. Authentification Client
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::processLogin');
    $routes->get('logout', 'AuthController::logout');

    // 2. Espace Client & Dépôt
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('depot', 'DashboardController::depotForm');
    $routes->post('depot', 'DashboardController::processDepot');

    // 3. Transactions & Historique
    $routes->get('retrait', 'TransactionController::retraitForm');
    $routes->post('retrait', 'TransactionController::processRetrait');
    $routes->get('transfert', 'TransactionController::transfertForm');
    $routes->post('transfert', 'TransactionController::processTransfert');
    $routes->get('historique', 'TransactionController::historique');
});

$routes->group('operateur/prefixes', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Prefixe::index');
    $routes->post('create', 'Prefixe::create');
    $routes->post('update/(:num)', 'Prefixe::update/$1');
    $routes->get('toggle/(:num)', 'Prefixe::toggle/$1');
    $routes->get('delete/(:num)', 'Prefixe::delete/$1');
});

$routes->group('operateur/baremes', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'BaremeFrais::index');
    $routes->get('create', 'BaremeFrais::createForm');
    $routes->post('create', 'BaremeFrais::create');
    $routes->get('edit/(:num)', 'BaremeFrais::editForm/$1');
    $routes->post('update/(:num)', 'BaremeFrais::update/$1');
    $routes->get('delete/(:num)', 'BaremeFrais::delete/$1');
});

$routes->group('operateur/rapports', ['filter' => 'auth'], function ($routes) {
    $routes->get('gains', 'Rapport::gains');
    $routes->get('comptes', 'Rapport::comptesClients');
});