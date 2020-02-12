<?php

# Route definition
$app->get('/', ['HomeController', 'index']);
$app->get('/logout', ['LoginController', 'logout']);
$app->post('/login', ['LoginController', 'login']);
