<?php

namespace Signature\Repository;

use Signature\Model\User;
use Signature\Service\Database;

class UserRepository 
{
    public function __construct(
        private Database $db,
    ) { }

    public function list(): array
    {
        $userList = [];

        $result = $this->db->list("users");
        foreach ($result as $userData) {
            $userList[] = $this->hydrate($userData);
        }

        return $userList;
    }

    public function listHaving(string $colName, $value): array
    {
        $userList = [];

        $result = $this->db->listHaving("users", $colName, $value);
        if ($result) {
            foreach ($result as $userData) {
                $userList[] = $this->hydrate($userData);
            }
        }
        

        return $userList;
    }

    public function add(array $values): User 
	{   
        $values['password'] = hash("sha256", $values['password']);
        return $this->find($this->db->add('users', $values));
	}

    public function edit(int $userId, array $values): User
    {   
        if (isset($values['password'])) {
            $values['password'] = hash("sha256", $values['password']);
        }
		$this->db->edit('users', $userId, $values);
        return $this->find($userId);
	}

    public function delete(int $userId)
	{
		$this->db->delete('users', $userId);
	}

	public function find(int $userId) : ?User
	{
		$userData = $this->db->find('users', $userId);
        
        if (!$userData) {
            return null;
        }

        return $this->hydrate($userData);
	}

    public function identify(string $login, string $password): ?User
    {
        $userData = $this->db->findBy(
            'users',
            [ 
                'login'=> $login, 
                'password' => hash("sha256",$password),
            ],
        );
        
        if (!$userData) {
            return null;
        }

        return $this->hydrate($userData);
    }

    public function doesLoginExist(string $login): bool
    {
        return !!$this->db->findBy('users', ['login' => $login]);
        
    }

    private function hydrate(array $userData): User 
    {
        $roles[] = User::ROLE_USER;

        if ($userData['isAdmin']) {
            $roles[] = User::ROLE_ADMIN;
        }

		$user = new User(
			$userData['id'],
			$userData['login'],
			$userData['password'],
			$userData['name'],
			$userData['firstName'],
			$userData['poste'],
            $userData['email'],
            $userData['numPro'],
            $roles,
		);

		return $user;
    }
}

?>