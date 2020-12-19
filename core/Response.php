<?php

namespace Core;

Use eftec\bladeone\BladeOne;

class Response
{
	protected int $statusCode = 200;
	protected string $views = __DIR__ . '/../views';
	protected string $cache = __DIR__ . '/../cache';
	protected object $blade;

	public function __construct()
	{
		$this->blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_AUTO);
	}

	/**
	 * Return buffer usefull for ajax|http request
	 * @param  string $view
	 * @param  array  $data
	 */
	public function viewBuffer(string $view, array $data = [])
	{
		ob_start();
		echo $this->blade->run($view, $data);
		$out = ob_get_clean();

		self::json(['post' => $out]);
	}

	/**
	 * Return view object
	 * @param  string $view
	 * @param  array  $data
	 */
	public function view(string $view, $data = []): void
	{
		echo $this->blade->run($view, $data);
	}

	/**
	 * Response as json
	 * @param  array  $data
	 */
	public function json(array $data = []): void
	{
		header('Content-type: application/json');
        echo json_encode($data);
	}
}
