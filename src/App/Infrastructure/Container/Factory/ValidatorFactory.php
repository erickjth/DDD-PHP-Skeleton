<?php

declare(strict_types=1);

namespace App\Infrastructure\Container\Factory;

use Psr\Container\ContainerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Validation;

class ValidatorFactory
{
	/**
	 * Invokable method
	 *
	 * @param  ContainerInterface $container Service container
	 *
	 * @return ValidatorInterface  Validator instance
	 */
	public function __invoke(ContainerInterface $container) : ValidatorInterface
	{
		$validator = Validation::createValidatorBuilder()
			->addYamlMapping(__DIR__.'/../../Resources/config/validation.yml')
			->getValidator();

		return $validator;
	}
}
