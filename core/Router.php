<?php

namespace Core;

use Core\Exceptions\RouteNotFoundException;
use Core\Traits\DebugTrait;

/**
 * @author Fil Beluan
 */
class Router
{
    use DebugTrait;

    /**
     * URI string
     * @var string
     */
    protected string $uri = '';

    /**
     * Request method get|post
     * @var string
     */
    protected string $requestMethod;

    /**
     * Route container
     * @var array
     */
    protected array $routes = [];

    /**
     * Add route to route container
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
     * Return uri
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
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
     * Return request method
     *
     * @param string
     * @return string
     */
    public function getAction()
    {
        return $this->requestMethod;
    }

    /**
     * Return handler
     *
     * @return string
     */
    public function getHandler()
    {
        if (($result = self::hasUri()) !== true) {
            return $result;
        }

        if (($result = self::hasUriMethod()) !== true) {
            return $result;
        }

        return $this->routes[$this->uri][$this->requestMethod];
    }

    /**
     * Check if has route uri
     *
     * @return boolean|int
     */
    private function hasUri()
    {
        if (! isset($this->routes[$this->uri])) {
            $this->uri = self::lookForPattern();

            if (empty($this->uri)) {
                http_response_code(404);
                return http_response_code();
            }
        }

        return true;
    }

    /**
     * Check if has method
     *
     * @return boolean|int
     */
    private function hasUriMethod()
    {
        if ( ! isset($this->routes[$this->uri][$this->requestMethod])) {
            if ($this->requestMethod === 'GET') {
                http_response_code(404);
                return http_response_code();
            } else {
                throw new MethodNotAllowedException;
            }
        }

        return true;
    }

    /**
     * Look for true route pattern
     *
     * @return string|empty
     */
    private function lookForPattern()
    {
        $keys = [];

        foreach ($this->routes as $key => $value) {
            # Here we breakdown the request uri into segments and same with key in $this->routes
            # get their count and compare if it match.
            #
            # This way, we will capture routes the has the same structure with the current
            # request uri.
            if (count($uriExploded = explode('/', $this->uri)) == count($keyExploded = explode('/', $key))) {
                $catch = self::matchBaseOnRoute($uriExploded, $keyExploded);

                if ($catch) {
                    $keys[] = $key;
                }
            }
        }

        return self::truthinessValue($keys, $uriExploded);
    }

    /**
     * Match the given uri to the routes
     *
     * @param array $uriExploded
     * @param array $keyExploded
     * @return string|empty
     */
    private function matchBaseOnRoute(array &$uriExploded = [], array &$keyExploded = [])
    {
        for ($i=0; $i < count($keyExploded); $i++) {
            if (empty($keyExploded) && empty($uriExploded)) {
                continue;
            }

            # Check if the string in $keyExploded[$i] is wrap with {}
            if (preg_match('/{\K[^}]*(?=})/m', $keyExploded[$i], $match)) {
                $_GET[$match[0]] = $uriExploded[$i]; # Issue 77
                continue;
            }

            if (($uriExploded[$i] !== $keyExploded[$i])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine the truthines of the route catch
     *
     * @param array $keys
     * @param array $uriExploded
     * @return string|empty
     */
    private function truthinessValue(array $keys = [], array $uriExploded = [])
    {
        $truthiness      = 0;
        $truthinessValue = '';

        foreach ($keys as $key => $value) {
            $valueExploded = explode('/', $value);

            $truth = 0;
            foreach ($valueExploded as $key2 => $value2) {
                if ($value2 === $uriExploded[$key2]) {
                    $truth++;
                }
            }

            if ($truth > $truthiness) {
                $truthiness      = $truth;
                $truthinessValue = $value;
            }
        }

        return $truthinessValue;
    }
}
