<?php

declare(strict_types=1);

namespace App\Framework\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface ExtensionInterface
{
    public function processConfiguration(ConfigurationInterface $configuration, array $configs);

    public function load(array $configs, ContainerBuilder $container);
}
