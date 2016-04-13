<?php

//phpinfo(); exit;

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Configurator;

$allowedIps = [
	'192.168.56.1',
];

$configurator->setDebugMode($allowedIps); // enable for your remote IP


$configurator->enableDebugger(__DIR__ . '/../log');

$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->createRobotLoader()
	->addDirectory(__DIR__)
    ->addDirectory(__DIR__ . '/../libs')
	->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');

$container = $configurator->createContainer();

return $container;
