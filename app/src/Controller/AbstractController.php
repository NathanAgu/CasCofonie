<?php 

namespace Signature\Controller;

use Signature\Service\Container;

abstract class AbstractController 
{
    public final function __construct(
        protected Container $container,
    ) { }

    protected function render(string $template, array $args = []): void
    {
        $this->container->getViewRenderer()->render($template, $args);
    }

    protected function redirect(string $path, int $code = 301)
    { 
        header(
            sprintf(
                "Location: %s://%s/%s",
                $_SERVER['REQUEST_SCHEME'],
                $_SERVER['HTTP_HOST'],
                ltrim($path, '/')
            ),
            true,
            $code
        );
        exit();
    }

    protected function getPublicDir() : string
    {
        return dirname(getcwd()).'/public/';
    }

    protected function addMessage(string $level, string $message): void
    {
        if (!isset($_SESSION['messages'][$level])) {
            $_SESSION['messages'][$level] = [];
        }
        $_SESSION['messages'][$level][] = $message;
    }
}
