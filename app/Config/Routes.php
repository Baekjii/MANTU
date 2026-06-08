<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'AuthController::login');
$routes->get('/login', 'AuthController::login');
$routes->post('/login/process', 'AuthController::processLogin');
$routes->get('/register', 'AuthController::register');
$routes->post('/register/process', 'AuthController::processRegister');
$routes->get('/logout', 'AuthController::logout');

$routes->get('/projects', 'ProjectController::index');
$routes->get('/projects/all', 'ProjectController::all');
$routes->get('/projects/create', 'ProjectController::create');
$routes->post('/projects/store', 'ProjectController::store');
$routes->post('/projects/update/(:num)', 'ProjectController::update/$1');
$routes->post('/projects/store_task_global', 'ProjectController::storeTaskGlobal');
$routes->get('/projects/delete/(:num)', 'ProjectController::delete/$1');

$routes->get('/projects/(:num)/tasks', 'TaskController::index/$1');
$routes->post('/projects/(:num)/tasks/store', 'TaskController::store/$1');
$routes->post('/tasks/update/(:num)', 'TaskController::update/$1');
$routes->get('/tasks/update_status/(:num)/(:segment)', 'TaskController::updateStatus/$1/$2');
$routes->post('/tasks/update_status_ajax', 'TaskController::updateStatusAjax');
$routes->get('/tasks/delete/(:num)', 'TaskController::delete/$1');

// Task Detail (comments, attachments, time_logs)
$routes->get('/tasks/(:num)', 'TaskController::detail/$1');
$routes->post('/tasks/(:num)/comments', 'TaskController::storeComment/$1');
$routes->get('/comments/delete/(:num)', 'TaskController::deleteComment/$1');
$routes->post('/tasks/(:num)/attachments', 'TaskController::storeAttachment/$1');
$routes->get('/attachments/delete/(:num)', 'TaskController::deleteAttachment/$1');
$routes->get('/attachments/download/(:num)', 'TaskController::downloadAttachment/$1');
$routes->get('/attachments/inline/(:num)', 'TaskController::inlineAttachment/$1');
$routes->post('/attachments/rename/(:num)', 'TaskController::renameAttachment/$1');
$routes->post('/tasks/(:num)/timelogs', 'TaskController::storeTimeLog/$1');
$routes->get('/timelogs/delete/(:num)', 'TaskController::deleteTimeLog/$1');

// Project Members
$routes->get('/projects/(:num)/members', 'ProjectMemberController::index/$1');
$routes->post('/projects/(:num)/members/add', 'ProjectMemberController::add/$1');
$routes->get('/members/remove/(:num)', 'ProjectMemberController::remove/$1');
$routes->post('/members/update-role/(:num)', 'ProjectMemberController::updateRole/$1');
$routes->post('/invitations/accept/(:num)', 'ProjectMemberController::acceptInvite/$1');
$routes->post('/invitations/decline/(:num)', 'ProjectMemberController::declineInvite/$1');

$routes->get('/profile', 'ProfileController::index');
$routes->post('/profile/update', 'ProfileController::update');
