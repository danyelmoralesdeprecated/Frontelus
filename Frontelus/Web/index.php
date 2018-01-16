<?php

session_start();
require_once __DIR__ . '/../app/Frontelus/Boot.class.php';

\Frontelus\Boot::run('Config\config.yml', 'Home');
$myApp = new Main(new View\Trackflag());
$myApp->start();
