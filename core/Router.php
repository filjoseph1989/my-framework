<?php

namespace Core;

use Core\Exceptions\RouteNotFoundException;

/**
 * @author Fil Beluan
 */
class Router
{
    /**
     * URI string
     * @var string
     */
    protected string $uri;

    /**
     * Request method get|post
     *
     * @var string
     */
    protected string $requestMethod;

    /**
     * Route container
     *
     * @var array
     */
    protected array $routes = [];

    /**
     * add route to route container
     *
     * @param  string $uri
     * @param  array $handler
     * @param  string $method
     * @return void
     */
    public function registerRoute($uri, $handler, $method)
    {
        $slash_found = preg_match('/^\//', $uri);

        # Prefixed uri with slash
        if ( ! $slash_found) {
            $uri = '/' . $uri;
        }

        $this->routes[$uri][$method] = $handler;
    }

    /**
     * Set route URI
     *
     * @param string $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * Set the request method
     *
     * @param string $action
     */
    public function setAction($action)
    {
        $this->requestMethod = $action;
    }

    /**
     * Return handler
     *
     * @return string
     */
    public function getHandler()
    {
        if ( ! isset($this->routes[$this->uri]) ) {
            throw new RouteNotFoundException;
        }

        if ( ! isset($this->routes[$this->uri][$this->requestMethod])) {
            throw new MethodNotAllowedException;
        }

        return $this->routes[$this->uri][$this->requestMethod];
    }
}
