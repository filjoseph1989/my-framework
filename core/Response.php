<?php

namespace Core;

Use eftec\bladeone\BladeOne;

class Response
{
	protected $statusCode = 200;

	/**
	 * Return view object
	 *
	 * @param  string $view
	 * @param  array  $data
	 * @return object
	 */
	public function view(string $view, $data = [])
	{
		$views = __DIR__ . '/../views';
		$cache = __DIR__ . '/../cache';

		$blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

		echo $blade->run($view, $data);
	}
}