<?php

declare(strict_types=1);

namespace App\Application\Command;

class GetIdentityByUuidCommand
{
	/**
	 * identity uuid
	 *
	 * @var int
	 */
	protected $identityUuid;

	/**
	 * Command constructor
	 *
	 * @param string $identityUuid
	 */
	public function __construct(string $identityUuid)
	{
		$this->identityUuid = $identityUuid;
	}

	/**
	 * Get identity uuid
	 *
	 * @return  int
	 */
	public function getIdentityUuid()
	{
		return $this->identityUuid;
	}
}
