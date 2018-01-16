<?php
require_once __DIR__ . '/../../app/Frontelus/Boot.class.php';
\Frontelus\Boot::run('Config\config.yml', 'API');

$myApp = new Main(new View\ApiView(), new Model\Extension());
$myApp->start();
