<?php

namespace Core;

use Core\Exceptions\InvalidRouteArgumentException;
use Core\Request\Request;
use Core\Traits\DebugTrait;

/**
 * @author Fil Beluan
 */
class App extends Core
{
    use DebugTrait;

    private object $container;

    /**
     * Initiate app instance
     */
    public function __construct()
    {
        parent::__construct();

        $this->container = new Container([
            'router' => function () {
                return new Router;
            },
            'response' => function () {
                return new Response;
            }
        ]);
    }

    /**
     * Run the application
     *
     * @return void
     */
    public function run()
    {
        session_start();

        $router = $this->container->router;

        $router->setUri($_SERVER['REQUEST_URI']);

        $router->setAction($_SERVER['REQUEST_METHOD']);

        $handler = $router->getHandler();

        self::route($handler);
    }

    /**
     * Register a get method route
     *
     * @param  string $uri
     * @param  array  $handler
     * @return void
     */
    public function get($uri, $handler)
    {
        $this->container->router->registerRoute($uri, $handler, 'GET');
    }

    /**
     * Register a post route
     *
     * @param  string $uri
     * @param  array  $handler
     * @return void
     */
    public function post($uri, $handler)
    {
        $this->container->router->registerRoute($uri, $handler, 'POST');
    }

    /**
     * call the controller
     *
     * @param  array $handler
     * @return object
     */
    public function route($handler)
    {
        if (is_array($handler)) {
            $class = "\\App\\Controllers\\{$handler[0]}";
            $handler[0] = new $class($this);
        }

        /*
        if ( ! is_callable($handler)) {
            # Issue 26: Should specify the kind of error message
            throw new InvalidRouteArgumentException;
        }
         */

        # Call the controller and passed parameters
        # Task 22: Check first what method is the request
        # Issue 27: here nag pass ko og request instance, pero, what daghan parameter required sa method?
        # Issue 28: can be use call_user_func_array instead
        # Issue 29: There should be a function that determined of the router required parameters
        return call_user_func($handler, (new Request));
    }

    /**
     * Return view
     *
     * @param  string $view
     * @param  array $data
     * @return object
     */
    public function view(string $view, $data = [])
    {
        $user = $_SESSION['user'] ?? null;
        $data['user'] = $user;

        return $this->container->response->view($view, $data);
    }
}
