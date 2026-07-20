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