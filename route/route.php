<?php

# Route definition
$app->get('/', ['HomeController', 'index']);
$app->post('/logout', ['LoginController', 'logout']);
$app->post('/login', ['LoginController', 'login']);
