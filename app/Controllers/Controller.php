<?php

namespace App\Controllers;

use Core\App;
use Core\Traits\DebugTrait;

abstract class Controller
{
	use DebugTrait;

	/**
	 * The app instance
	 * @var object
	 */
	private $app;

	/**
	 * The view instance
	 * @var object
	 */
	private $view;

	/**
	 * Initiate app instance
	 *
	 * @param App $app
	 */
	public function __construct(App $app)
	{
		$this->app = $app;
	}

	/**
	 * Return the app instance
	 *
	 * @return object
	 */
	protected function app()
	{
		return $this->app;
	}

	/**
	 * Destruct this controller
	 */
	public function __destruct() {}
}
