<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

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