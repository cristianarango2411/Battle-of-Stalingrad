<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

$app = new Application();
/*
$app->get('/', function() {
    return 'Hello, world!';
});

$app->get('/my-endpoint', function() {
    return new Response('Hello, Endpoind!', 200);
});*/

$app->get('/api/v1/tanks/{tank_id}', function($tank_id) use ($app) {
    // Replace this with code to load the tank from the database
    return "Tank: " . $tank_id;
});

// Load game map from database
$app->get('/api/v1/map/{map_id}', function($map_id) use ($app) {
    // Replace this with code to load the map from the database
    return "Map: " . $map_id;
});

// Simulate
$app->get('/api/v1/simulate/', function() use ($app) {
    // Replace this with code to perform the simulation
    return "Simulation";
});

// Display battle score in JSON format
$app->get('/api/v1/score/{score_id}', function($score_id) use ($app) {
    // Replace this with code to load the score from the database and return it in JSON format
    return "Score: " . $score_id;
});

// Daily Leaderboard
$app->get('/api/v1/leaderboard/daily', function() use ($app) {
    // Replace this with code to display the daily leaderboard
    return "Daily Leaderboard";
});

// Monthly Leaderboard
$app->get('/api/v1/leaderboard/monthly', function() use ($app) {
    // Replace this with code to display the monthly leaderboard
    return "Monthly Leaderboard";
});

// Leaderboard
$app->get('/api/v1/leaderboard/', function() use ($app) {
    // Replace this with code to display the leaderboard
    return "Leaderboard";
});

$app->post('/api/v1/score', function(Request $request) use ($app) {
    $score = $request->get('score');
    // Replace this with code to save the score to the database
    return "Score saved: " . $score;
});

$app->run();