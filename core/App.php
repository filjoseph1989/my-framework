<?php

namespace Core;

/**
 * @author Fil Beluan
 */
class App extends Core
{
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
        $router = $this->container->router;

        $router->setUri($_SERVER['REQUEST_URI']);

        $router->setAction($_SERVER['REQUEST_METHOD']);

        $handler = $router->getHandler();

        $response = $this->route($handler);

        # Task 20: Study more on this especially for api, and post method
        // echo $this->respond($response);
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

        if ( ! is_callable($handler)) {
            throw new InvalidRouteArgumentException;
        }

        # Call the controller and passed parameters
        # Task 22: Check first what method is the request
        return call_user_func($handler, $this);
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
        return $this->container->response->view($view, $data);
    }
}
