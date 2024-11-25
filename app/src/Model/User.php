<?php

namespace Signature\Model;


class User
{
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';

    public function __construct(
        public int $id,
        public string $login,
        public string $password,
        public ?string $name = null,
        public ?string $firstName = null,
        public ?string $poste = null,
        public ?string $email = null,
        public ?string $numPro = null,
        public array $roles = [],
    ) { }

    public function isAdmin(): bool 
    {
        return in_array(self::ROLE_ADMIN, $this->roles);
    }

}
