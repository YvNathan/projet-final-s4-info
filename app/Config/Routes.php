<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('/', 'AuthClientController::login');
$routes->get('login', 'AuthClientController::login');
$routes->post('login', 'AuthClientController::doLogin');
$routes->get('logout', 'AuthClientController::logout');

$routes->get('home', 'ClientController::index', ['filter' => 'auth']);
$routes->post('depot', 'ClientController::doDepot');
$routes->post('retrait', 'ClientController::doRetrait');
$routes->post('transfert', 'ClientController::doTransfert');
$routes->get('home', 'HomeController::index', ['filter' => 'auth']);

$routes->get('operateur/situation', 'SituationController::index');

$routes->get('operateur/config', 'ConfigController::index');
$routes->post('operateur/config', 'ConfigController::store');
