<?php

namespace Signature\Service;

class Router
{
    public function __construct(
        private Container $container,
        private array $routeDefinitions
    ) { }

    public function find(string $uri): ?callable
    {   
        $uri = explode('?', $uri)[0];
        $uri = \rtrim($uri, '/');
        
        foreach ($this->routeDefinitions as $path => $data) {
            if ($uri === $path) {
                $controller = new $data[0]($this->container);

                return fn () => call_user_func([$controller, $data[1]]);
            }
        }

        return null;
    }
}