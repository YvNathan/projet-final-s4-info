<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('login', 'AuthClientController::login');
$routes->post('login', 'AuthClientController::doLogin');
$routes->get('logout', 'AuthClientController::logout');
