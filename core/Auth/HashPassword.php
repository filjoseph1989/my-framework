<?php

namespace Core\Auth;

use Core\Contracts\HashPasswordInterface;

/**
 * Class for password
 *
 * @author Fil Beluan
 */
class HashPassword implements HashPasswordInterface
{
	/**
	 * Hash the given password
	 *
	 * @param  string $password
	 * @return string
	 */
	public function hashPassword(string $password)
	{
		# 50 milliseconds
		$timeTarget     = 0.05;
		$cost           = 8;
		$hashedPassword = '';

		do {
			$cost++;
			$start          = microtime(true);
			$hashedPassword = password_hash($password, PASSWORD_BCRYPT, ["cost" => $cost]);
			$end            = microtime(true);
		} while (($end - $start) < $timeTarget);

		return $hashedPassword;
	}
}
