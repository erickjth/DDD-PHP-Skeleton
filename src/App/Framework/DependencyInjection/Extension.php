<?php

namespace App\Framework\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\Extension\Extension as BaseExtension;

abstract class Extension extends BaseExtension
{
	public function setConfiguration(ConfigurationInterface $configuration, array $configs)
	{
		$this->processConfiguration($configuration, $configs);
	}
}
