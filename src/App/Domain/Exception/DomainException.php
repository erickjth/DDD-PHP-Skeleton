<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use DomainException as PhpDomainException;

class DomainException extends PhpDomainException
{
	const DOMAIN_ERROR = 1;
	const ENTITY_NOT_FOUND = 2;
	const DUPLICATED_ENTITY = 3;
}
