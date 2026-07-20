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
