<?php

namespace App\Infrastructure\Container;

use App\Framework\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class InfrastructureExtension extends Extension
{
	/**
	 * {@inheritDoc}
	 */
	public function load(array $configs, ContainerBuilder $container)
	{
		$loader = new YamlFileLoader(
			$container,
			new FileLocator(__DIR__.'/../Resources/config')
		);

		$loader->load('services.yml');

		// Add Passes after load
		$container->addCompilerPass(new MessageHandlerCompilerPass());
	}
}
