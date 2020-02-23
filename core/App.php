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

    /**
     * Application container
     * @var object
     */
    private object $container;

    /**
     * Setting for testing mode
     * @var boolean
     */
    private $test;

    /**
     * URI Container
     * @var string
     */
    private string $uri = '';

    /**
     * Container for request method (get/post) to be call
     * @var string
     */
    private string $action = '';

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

        $router->setUri(self::getUri());

        $router->setAction(self::getAction());

        $handler = $router->getHandler();

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        self::setEnv();

        return self::route($handler);
    }

    /**
     * Set URI, used by testing
     *
     * @param string $uri
     */
    public function setUri(string $uri = '')
    {
        $this->uri = $uri;
    }

    /**
     * Return URI
     *
     * @return string
     */
    public function getUri()
    {
        if (empty($this->uri)) {
            return $_SERVER['REQUEST_URI'] ?? '/';
        }

        return $this->uri;
    }

    /**
     * Set the request method
     *
     * @param string $action
     */
    public function setAction(string $action = '')
    {
        $this->action = $action;
    }

    /**
     * Return action
     *
     * @return string
     */
    public function getAction()
    {
        if (empty($this->action)) {
            return $_SERVER['REQUEST_METHOD'] ?? 'index';
        }

        return $this->action;
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
        if (self::isTest()) {
            self::setAction('GET');
        }

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
        if (self::isTest()) {
            self::setAction('POST');
        }

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
        self::setAuth($data);

        if (self::isTest()) {
            return $data;
        }

        return $this->container->response->view($view, $data);
    }

    /**
     * Set mode
     *
     * @param  boolean $testing
     * @return void
     */
    public function mode($mode = '')
    {
        if ($mode == 'testing') {
            $this->test = true;
        }
    }

    /**
     * Visit route
     *
     * @param  string $uri
     * @param  string $action
     * @return void
     */
    public function visit(string $uri = '')
    {
        self::setUri($uri);
    }

    /**
     * Set auth data
     *
     * @param void
     */
    private function setAuth(&$data)
    {
        $data['user'] = $_SESSION['user'] ?? null;;
    }

    /**
     * Check if environment is testing
     *
     * @return boolean
     */
    public function isTest()
    {
        if ($this->test === true) {
            return true;
        }

        return false;
    }

    /**
     * Set database setting
     *
     * Issue 30
     */
    private function setEnv()
    {
        putenv('DB_HOST=127.0.0.1');
        putenv('DB_USERNAME=root');
        putenv('DB_PASSWORD=password');
        putenv('DB_DATABASE=crateclub2');
        putenv('DB_PORT=3306');
    }
}
