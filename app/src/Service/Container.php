<?php

namespace Signature\Service;

use Signature\Model\User;
use Signature\Repository\UserRepository;

class Container
{
    private ?User $currentUser = null;

    private ?ViewRenderer $viewRenderer = null;
    
    private ?Database $database = null;
    private ?UserRepository $userRepository = null;

    public function setCurrentUser(User $user)
    {
        $this->currentUser = $user;
    }

    public function loginUser($user): void 
    {   
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION['user_id'] = $user->id;
        $this->currentUser = $user;
    }

    public function logoutUser(): void
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    public function getCurrentUser(): ?User
    {
        return $this->currentUser;
    }

    public function getDatabase(): Database
    {
        if ($this->database === null) {
            $this->database = new Database();
        }

        return $this->database;
    }

    public function getUserRepository(): UserRepository
    {

        if ($this->userRepository === null) {
            $this->userRepository = new UserRepository(
                $this->getDatabase()
            );
        }

        return $this->userRepository;
    }

    public function getViewRenderer(): ViewRenderer
    {
        if ($this->viewRenderer === null) {
            $this->viewRenderer = new ViewRenderer($this);
        }

        return $this->viewRenderer;
    }
}