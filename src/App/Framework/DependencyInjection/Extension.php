<?php

namespace App\Framework\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;

abstract class Extension implements ExtensionInterface
{
	public function processConfiguration(ConfigurationInterface $configuration, array $configs)
	{
		$processor = new Processor();

		return $processor->processConfiguration($configuration, $configs);
	}
}
