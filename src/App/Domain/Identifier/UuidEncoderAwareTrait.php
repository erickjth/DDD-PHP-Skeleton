<?php

declare(strict_types=1);

namespace App\Domain\Identifier;

trait UuidEncoderAwareTrait
{
	protected $uuidEncoder;

	public function getUuidEncoder()
	{
		return $this->uuidEncoder;
	}

	public function setUuidEncoder(UuidEncoder $uuidEncoder)
	{
		$this->uuidEncoder = $uuidEncoder;
	}
}
