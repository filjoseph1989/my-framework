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
            },
            'request' => function () {
                return new Request;
            }
        ]);
    }

    /**
     * Run the application
     *
     * @return null
     */
    public function run()
    {
        $router = $this->container->router;

        $router->setUri(self::getUri());

        $router->setAction(self::getAction());

        $handler = $router->getHandler();

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
            if (empty($_SESSION['token'])) {
                $_SESSION['token'] = bin2hex(random_bytes(32));
            }
        }

        return self::route($handler);
    }

    /**
     * call the controller
     *
     * @param  array $handler
     * @return object
     */
    public function route($handler)
    {
        if ($handler == 404) {
            return self::notFound();
        }

        if (is_array($handler)) {
            $class      = "\\App\\Controllers\\{$handler[0]}";
            $handler[0] = new $class($this);
            $params     = self::getParameters($handler);
        }

        if ( ! is_callable($handler)) {
            throw new InvalidRouteArgumentException;
        }

        # Call the controller and passed parameters
        return call_user_func_array($handler, $params);
    }

    /**
     * Display not found view
     *
     * @return object
     */
    public function notFound()
    {
        return self::view('404');
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
            return $_SERVER['REQUEST_METHOD'];
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
     * Return view
     * Issue 54
     *
     * @param  string $view
     * @param  array $data
     * @return object
     */
    public function view(string $view, $data = [])
    {
        self::setAuth($data);
        self::setWith($data);
        self::setErrors($data);
        self::unsetEmptyWithData($data);
        self::setToken($data);
        self::setCurrentPage($data, $view);
        self::setResponse($data);
        self::setViewUri($data);
        self::setViewSiteName($data);

        if (self::isTest()) {
            return $data;
        }

        return $this->container->response->view($view, $data);
    }

    /**
     * Response as json
     *
     * @param array $data
     * @return void
     */
    public function json(array $data = [])
    {
        self::setResponse($data);

        if (self::isTest()) {
            return $data;
        }

        return $this->container->response->json($data);
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
     * Set token
     *
     * @param array $data
     */
    private function setToken(&$data)
    {
        $data['token'] = $_SESSION['token'] ?? '';
    }

    /**
     * Set session errors
     *
     * @param array $data
     */
    private function setErrors(&$data)
    {
        if (isset($data['with']['errors'])) {
            $data['errors'] = $data['with']['errors']; # Issue 45
            unset($data['with']['errors']);
        }
    }

    /**
     * Set with data
     *
     * @param array $data
     */
    private function setWith(&$data)
    {
        if (isset($_SESSION['with'])) {
            $data['with'] = $_SESSION['with'];
            unset($_SESSION['with']);
        }
    }


    /**
     * Set the current page name
     *
     * @param array $data
     * @param string $view
     * @return void
     */
    private function setCurrentPage(&$data, string &$view)
    {
        $_SESSION['current_page'] = $view; # Issue 48
        $data['curren_page']      = $view;
    }

    /**
     * Set the current response
     *
     * @param [type] $data
     * @return void
     */
    private function setResponse(&$data)
    {
        http_response_code(200);
        $data['response'] = http_response_code();
    }

    /**
     * Set URI as view variable
     *
     * @param void
     */
    private function setViewUri(&$data)
    {
        $data['uri'] = $this->uri;
    }

    /**
     * Set site name
     *
     * @param void
     */
    private function setViewSiteName(&$data)
    {
        $data['site_name'] = $_SERVER['SERVER_NAME'];
    }

    /**
     * Unset $with if empty
     *
     * @param array $data
     */
    private function unsetEmptyWithData(&$data)
    {
        if (empty($data['with'])) {
            unset($data['with']);
        }
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
     * Return parameters
     *
     * @param  array  $handler
     * @return array
     */
    private function getParameters(array $handler=[])
    {
        $reflection = new \ReflectionMethod($handler[0], $handler[1]);

        foreach ($reflection->getParameters() as $param) {
            if (isset($_GET[$param->getName()])) {
                $params[$param->getPosition()] = $_GET[$param->getName()]; # Issue 78
            }
            if (!is_null($param->getClass()) && $param->getClass()->name == "Core\Request\Request") {
                $params[$param->getPosition()] = $this->container->request;
            }
        }

        return $params ?? [];
    }
}
