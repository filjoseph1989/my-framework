<?php

namespace App\Controllers;

/**
 * Class for home page
 *
 * @author Fil Elman
 */
class HomeController extends Controller
{
	/**
	 * Display the homepage
	 *
	 * @return object
	 */
	public function index()
	{
		return $this->app()->view('index');
	}
}
