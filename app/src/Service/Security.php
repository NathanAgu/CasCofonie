<?php

namespace Signature\Service;

use Exception;

class Security
{
    public function __construct(
        private Container $container,
        private array $firewallDefinitions
    ) { }

    public function start(): self
    {
        session_start();
        
        $user = null;
        if(isset($_SESSION['user_id']) && $_SESSION['user_id']) {
            $user = $this
                ->container
                ->getUserRepository()
                ->find($_SESSION['user_id'])
            ;
            if($user) {
                $this->container->setCurrentUser($user);
            }
        }

        return $this;
    }

    public function check(string $uri): void
    {
        $this->start();

        if (in_array($uri, ['/login', '/register'])) {
            return;
        }


        if (!$user = $this->container->getCurrentUser()) {
            header(
                sprintf(
                    "Location: %s://%s/%s",
                    $_SERVER['REQUEST_SCHEME'],
                    $_SERVER['HTTP_HOST'],
                    'login'
                ),
                true,
                301
            );
            exit();
        }
        foreach ($this->firewallDefinitions as $path => $roles) {
            if (str_starts_with($uri, $path)) {
                if (array_intersect($user->roles, $roles)) {
                    return;
                } else {
                    throw new Exception("Not authorized");
                }
            }
        }
    }
}