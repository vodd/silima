<?php

require '../vendor/autoload.php';
session_start();
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

require '../app/container.php';
$container = $app->getContainer();
# Middleware
$app->add($container->get('csrf'));


$app->get('/',\App\Controllers\PagesController::class.':home');
$app->get('/new',\App\Controllers\PagesController::class.':getNew')->setName('new');
$app->post('/new',\App\Controllers\PagesController::class.':postNew');
$app->get('/contact',\App\Controllers\PagesController::class.':getContact')->setName('contact');
$app->get('/reservation',\App\Controllers\PagesController::class.':getRes')->setName('reservation');
$app->post('/reservation',\App\Controllers\PagesController::class.':postRes');
$app->post('/contact',\App\Controllers\PagesController::class.':postContact');
$app->get('/admin',\App\Controllers\AdminsController::class.':index');




$app->run();

