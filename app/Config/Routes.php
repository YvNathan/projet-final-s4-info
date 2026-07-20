<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('/', 'AuthClientController::login');
$routes->get('login', 'AuthClientController::login');
$routes->post('login', 'AuthClientController::doLogin');
$routes->get('operateur/login', 'SituationController::index');
$routes->get('logout', 'AuthClientController::logout');

$routes->get('home', 'ClientController::index', ['filter' => 'auth:client']);
$routes->post('depot', 'ClientController::doDepot', ['filter' => 'auth:client']);
$routes->post('retrait', 'ClientController::doRetrait', ['filter' => 'auth:client']);
$routes->post('transfert', 'ClientController::doTransfert', ['filter' => 'auth:client']);
$routes->get('historique', 'ClientController::historique', ['filter' => 'auth:client']);

$routes->get('operateur/situation', 'SituationController::index');

$routes->get('operateur/config', 'ConfigController::index');
$routes->post('operateur/config', 'ConfigController::store');
$routes->post('operateur/config/(:num)/update', 'ConfigController::update/$1');
$routes->post('operateur/config/(:num)/delete', 'ConfigController::delete/$1');

$routes->get('operateur/frais', 'FraisOperationController::index');
$routes->post('operateur/frais', 'FraisOperationController::store');
$routes->post('operateur/frais/(:num)/update', 'FraisOperationController::update/$1');
$routes->post('operateur/frais/(:num)/delete', 'FraisOperationController::delete/$1');

$routes->get('operateur/operateurs', 'OperateurController::index');
$routes->post('operateur/operateurs', 'OperateurController::store');
$routes->post('operateur/operateurs/(:num)/update', 'OperateurController::update/$1');
$routes->post('operateur/operateurs/(:num)/delete', 'OperateurController::delete/$1');
