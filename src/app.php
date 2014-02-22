<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../web/templates',
));


$app->get('/', function () use ($app) {
    return $app['twig']->render('signin.html.twig');
});

$app->get('/hello/', function () use ($app) {
    return $app['twig']->render('layout.html.twig', array(
        'user' => 'John Doe'
    ));
});

return $app;
