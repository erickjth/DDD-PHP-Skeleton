<?php

declare(strict_types=1);

use App\Framework\DependencyInjection\Config;
use JSoumelidis\SymfonyDI\Config\ContainerFactory;

$config  = require realpath(__DIR__) . '/config.php';

$factory = new ContainerFactory();

$container = $factory(new Config($config));

$container->compile();

return $container;
