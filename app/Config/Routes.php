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

/*
 ====================================================================
 🔧 ROUTE GROUPE : OPERATEUR
 ====================================================================
 */
$routes->group('operateur', ['namespace' => 'App\Controllers\Operateur'], static function ($routes) {
    // Routes publiques (authentification)
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::processLogin');
    $routes->get('logout', 'AuthController::logout');
});

// Routes protégées de l'opérateur
$routes->group('operateur', ['namespace' => 'App\Controllers\Operateur', 'filter' => 'operateurAuth'], static function ($routes) {
    
    // Gestion des préfixes
    $routes->group('prefixes', function ($routes) {
        $routes->get('/', 'Prefixe::index');
        $routes->post('create', 'Prefixe::create');
        $routes->get('edit/(:num)', 'Prefixe::editForm/$1');
        $routes->post('update/(:num)', 'Prefixe::update/$1');
        $routes->get('toggle/(:num)', 'Prefixe::toggle/$1');
        $routes->get('delete/(:num)', 'Prefixe::delete/$1');
    });

    // Gestion des barèmes de frais
    $routes->group('baremes', function ($routes) {
        $routes->get('/', 'BaremeFrais::index');
        $routes->get('create', 'BaremeFrais::createForm');
        $routes->post('create', 'BaremeFrais::create');
        $routes->get('edit/(:num)', 'BaremeFrais::editForm/$1');
        $routes->post('update/(:num)', 'BaremeFrais::update/$1');
        $routes->get('delete/(:num)', 'BaremeFrais::delete/$1');
    });

    // Gestion des opérateurs
    $routes->group('operateurs', function ($routes) {
        $routes->get('/', 'Operateur::index');
        $routes->post('update/(:num)', 'Operateur::update/$1');
    });

    // Rapports et statistiques
    $routes->group('rapports', function ($routes) {
        $routes->get('gains', 'Rapport::gains');
        $routes->get('comptes', 'Rapport::comptesClients');
        $routes->get('montants-a-envoyer', 'Rapport::montantsAEnvoyer');
    });
});

$routes->group('admin/prefixes', function ($routes) {
    $routes->get('/', 'Prefixe::index');
    $routes->post('add', 'Prefixe::create');
    $routes->get('delete/(:num)', 'Prefixe::delete/$1');
    $routes->get('toggle/(:num)', 'Prefixe::toggle/$1');
});

$routes->group('admin/baremes', function ($routes) {
    $routes->get('/', 'BaremeFrais::index');
    $routes->post('update/(:num)', 'BaremeFrais::update/$1');
});

$routes->group('admin/rapports', function ($routes) {
    $routes->get('gains', 'Rapport::gains');
    $routes->get('clients', 'Rapport::comptesClients');
});
