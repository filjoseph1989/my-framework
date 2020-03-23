<?php

namespace Core\Contracts;

/**
 * A contract for implementing hash password
 *
 * @author Fil Beluan
 */
interface HashPasswordInterface
{
	/**
	 * Hash the given password
	 *
	 * @param  string $password
	 * @return string
	 */
	public function hashPassword(string $password);
}
