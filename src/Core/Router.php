<?php 

namespace Trimania\Core;

class Router
{
	private $routes = [];

	public function method()
    {
        return isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'cli';
    }

    public function uri()
    {
        $self = isset($_SERVER['PHP_SELF']) ? str_replace('index.php/', '', $_SERVER['PHP_SELF']) : '';
        $uri = isset($_SERVER['REQUEST_URI']) ? explode('?', $_SERVER['REQUEST_URI'])[0] : '';
        if ($self !== $uri) {
            $peaces = explode('/', $self);
            array_pop($peaces);
            $start = implode('/', $peaces);
            $search = '/' . preg_quote($start, '/') . '/';
            $uri = preg_replace($search, '', $uri, 1);
        }
        return $uri;
    }

    public function call($callback, $parameters)
    {
        if (is_callable($callback)) {
            return call_user_func_array($callback, $parameters);
        }
        return null;
    }

	public function on($method, $path, $callback)
    {
        $method = strtolower($method);
        
        if (!isset($this->routes[$method])) {
            $this->routes[$method] = [];
        }

        $uri = substr($path, 0, 1) !== '/' ? '/' . $path : $path;
        $pattern = str_replace('/', '\/', $uri);
        $route = '/^' . $pattern . '$/';
        
        $this->routes[$method][$route] = $callback;
        
        return $this;
    }

    public function run()
    {
    	$uri = $this->uri();
    	$method = $this->method();

        foreach ($this->routes[$method] as $route => $callback) {
            if (preg_match($route, $uri, $parameters)) {
                array_shift($parameters);
                return $this->call($callback, $parameters);
            }
        }
        return null;
    }
}