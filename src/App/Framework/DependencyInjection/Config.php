<?php

declare(strict_types=1);

namespace App\Framework\DependencyInjection;

use JSoumelidis\SymfonyDI\Config\Config as BaseConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\Framework\DependencyInjection\ExtensionInterface;
use ReflectionClass;
use InvalidArgumentException;

class Config extends BaseConfig
{
	/**
	 * @var array
	 */
	private $config;

	public function __construct(array $config, $servicesAsSynthetic = false)
	{
		parent::__construct($config, $servicesAsSynthetic);

		$this->config = $config;
	}

	public function configureContainerBuilder(ContainerBuilder $builder)
	{
		$config = $this->config;

		if (isset($config['dependencies']) && is_array($config['dependencies']))
		{
			$dependencies = $config['dependencies'];

			if (isset($dependencies['extensions']) && is_array($dependencies['extensions']))
			{
				$extensions = $dependencies['extensions'];

				unset($this->config['dependencies']['extensions']);

				foreach ($extensions as $module => $class)
				{
					if (class_exists($class) === false)
					{
						continue;
					}

					if (is_string($module) === false)
					{
						throw new InvalidArgumentException("Extensions must be an associated array with pairs like \"name\" => \"class\".");
					}

					$extensionReflector = new ReflectionClass($class);

					if ($extensionReflector->implementsInterface(ExtensionInterface::class) === false)
					{
						continue;
					}

					$extensionObject = $extensionReflector->newInstance();

					$moduleConfig = $this->config[$module] ?? [];

					$configurationClass = substr_replace($class, '\Configuration', strrpos($class, '\\'));

					if (class_exists($class) === true)
					{
						$configuration = new $configurationClass();
						$extensionObject->processConfiguration($configuration, $moduleConfig);
					}

					$extensionObject->load($moduleConfig, $builder);
				}
			}
		}


		parent::configureContainerBuilder($builder);
	}
}
