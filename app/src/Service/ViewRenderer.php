<?php

namespace Signature\Service;

use Twig\Environment;
use Twig\Extra\Intl\IntlExtension;
use Twig\Loader\FilesystemLoader;

class ViewRenderer
{
    private ?Environment $twig = null;

    public function __construct(
        private Container $container,
    ){ }

    public function render(string $template, array $vars = []): void 
    {
        $messages = $_SESSION['messages'] ?? [];
        unset($_SESSION['messages']);
        
        $currentUser = $this->container->getCurrentUser();

        echo $this->getTwig()->render($template, [
            'messages' => $messages,
            'currentUser' => $currentUser,
            ...$vars
        ]);
    }

    public function getTwig(): Environment {
        if ($this->twig) {
          return $this->twig;
        }
        $loader = new FilesystemLoader('../src/View');
        $this->twig  = new \Twig\Environment($loader, [
            // 'cache' => '../cache',
        ]);
        $this->twig->addExtension(new IntlExtension());

        return $this->twig;
    }
}