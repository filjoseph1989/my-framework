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

    protected string $uri = '';
    protected string $requestMethod;
    protected array $routes = [];
    protected array $handler = [];

    /**
     * Add route to route container
     * @param  string $uri
     * @param  array $handler   A controller and method
     * @param  string $httpverb HTTP Request verb (e.g POST, GET)
     */
    public function registerRoute($uri, $handler, $httpverb)
    {
        $slash_found = preg_match('/^\//', $uri);

        # Prefixed uri with slash
        if ( ! $slash_found) {
            $uri = '/' . $uri;
        }

        $this->routes[$uri][$httpverb] = $handler;
    }

    /**
     * Set route URI
     * @param string $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    # Return URI
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Set the request method
     * @param string $action
     */
    public function setAction($action)
    {
        $this->requestMethod = $action;
    }

    /**
     * Return request method
     * @param string
     */
    public function getAction(): string
    {
        return $this->requestMethod;
    }

    # Return handler
    public function getHandler(): array|int
    {
        # Return cached
        if (isset($this->handler[$this->uri])) {
            return $this->handler[$this->uri];
        }

        if (($result = self::hasUri()) !== true) {
            return $result;
        }

        if (($result = self::hasUriMethod()) !== true) {
            return $result;
        }

        # Cache routes
        $this->handler[$this->uri] = $this->routes[$this->uri][$this->requestMethod];

        return $this->routes[$this->uri][$this->requestMethod];
    }

    # Check if has route uri
    private function hasUri(): bool|int
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

    # Check if has method
    private function hasUriMethod(): bool|int
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

    # Look for true route pattern
    private function lookForPattern(): string
    {
        $keys = [];

        foreach ($this->routes as $key => $value) {
            # Here we breakdown the request uri into segments and same with key in $this->routes
            # get their count and compare if it match.

            # This way, we will capture routes the has the same structure with the current
            # request uri.
            if (count($uriExploded = explode('/', $this->uri)) == count($keyExploded = explode('/', $key))) {
                $catch = self::matchBaseOnRoute($uriExploded, $keyExploded);
                if ($catch) { $keys[] = $key; }
            }
        }

        return self::truthinessValue($keys, $uriExploded);
    }

    /**
     * Match the given uri to the routes
     * @param array $uriExploded
     * @param array $keyExploded
     */
    private function matchBaseOnRoute(array &$uriExploded = [], array &$keyExploded = []): bool
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
     * @param array $keys
     * @param array $uriExploded
     */
    private function truthinessValue(array $keys = [], array $uriExploded = []): string
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
