<?php
require_once __DIR__ . '/../../app/Frontelus/Boot.class.php';
\Frontelus\Boot::run('Config\config.yml', 'User');

$myApp = new Main(new View\UserView(), new Model\ModelHelper());
$myApp->start();
