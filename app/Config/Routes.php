<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

$routes->get('/', 'AuthController::index');
$routes->match(['get', 'post'], 'register', 'AuthController::register');
$routes->match(['get', 'post'], 'login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');
$routes->get('dashboard', 'UserController::dashboard', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'profile', 'UserController::profile', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'search', 'UserController::search', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'ajax-search', 'UserController::ajaxSearch', ['filter' => 'authGuard']);



