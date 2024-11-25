<?php

namespace Signature\Service;

use Exception;
use PDO;
use PDOException;
use PDOStatement;

class Database
{
	private $host;
	private $login;
	private $passwd;
	private $base;
	private PDO $connection;
	private $port;

	public function __construct() 
	{
		$this->host = "mariadb";
		$this->port = "3306";
		$this->login = "root";
		$this->passwd = "password";
		$this->base = "template";
		$this->connection();
	}

	private function connection()
	{
		try {
			$this->connection = new PDO("mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->base . ";charset=utf8", $this->login, $this->passwd);
		} catch (PDOException $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function list(string $table): ?array
	{
		$data = null;
		$nbTuples = 0;
		$stringQuery = "SELECT * FROM $this->base.".$table;
		$query = $this->connection->prepare($stringQuery);
		if ($query->execute()) {
			while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
				$data[$nbTuples] = $row;
				$nbTuples++;
			}
		} else {
			throw new Exception($query->errorCode());
		}

		return $data;
	}

	public function listHaving(string $table, string $column, $value): ?array
	{
		$data = null;
		$nbTuples = 0;
		$query = $this->connection->prepare(
			<<<SQL
			SELECT * FROM $this->base.$table 
			WHERE $column = :value;
			SQL
		);

		$params['value'] = $value;

		if ($query->execute($params)) {
			while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
				$data[$nbTuples] = $row;
				$nbTuples++;
			}
		} else {
			throw new Exception($query->errorCode());
		}

		return $data;
	}

	public function find(string $table, int $id): ?array
	{
		$query = $this->connection->prepare(
			<<<SQL
			SELECT * FROM $this->base.$table 
			WHERE id = :id;
			SQL
		);
		$params = [
			'id' => $id,
		];
		if ($query->execute($params)) {
			$row = $query->fetch(PDO::FETCH_ASSOC);
			
			return $row;
		} else {
			throw new Exception($query->errorCode());
		}
	}

	public function findBy(string $table, array $conditions): ?array
	{
		$queryString = "SELECT * FROM $this->base.$table WHERE ";
		$params = [];

		foreach ($conditions as $key => $value) {
			$queryParams[] = $table . '.' . $key . ' = :' . $key;
			$params[$key] = $value; 
		}

		$queryString .= implode(' && ',$queryParams);

		$query = $this->connection->prepare(
			$queryString,
			$params
		);

		if ($query->execute($params)) {
			$row = $query->fetch(PDO::FETCH_ASSOC);

			if (!$row) {
				return null;
			}
			
			return $row;
		} else {
			throw new Exception($query->errorCode());
		}
	}

	public function edit(string $table, int $id, array $values) 
	{
		$queryString = "UPDATE $this->base.$table SET ";
		$queryParams = [];
		$params = [];

		foreach ($values as $key => $value) {
			$queryParams[] = $key . ' = :' . $key;
			$params[$key] = $value; 
		}

		$queryString .= implode(', ',$queryParams);

		$queryString .= ' WHERE id = :id;';
		$params['id'] = $id; 

		$query = $this->connection->prepare(
			$queryString,
			$params
		);

		try {
			if (!$query->execute($params)) {
				throw new Exception($query->errorCode());
			}
		} catch(PDOException $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function add(string $table, array $values) : int 
	{
		$queryStringValues = [];

		$params = [
			...$values
		];
		$queryStringColumns = implode(
			', ', 
			array_keys($values)
		);
		$queryStringValues = implode(
			', :', 
			array_keys($values)
		);

		$query = $this->connection->prepare(
			<<<SQL
			INSERT INTO $this->base.$table($queryStringColumns) VALUES(:$queryStringValues);
			SQL
		);
		
		try {
			if (!$query->execute($params)) {
				throw new Exception($query->errorCode());
			}
		} catch(PDOException $e) {
			throw new Exception($e->getMessage());
		}
		
		return $this->connection->lastInsertId();
	}
	
	public function delete(string $table, int $id)
	{
		$query = $this->connection->prepare(
			<<<SQL
			DELETE FROM $this->base.$table
			WHERE id = :id;
			SQL
		);
		$params = [		
			'id' => $id
		];

		try {
			if (!$query->execute($params)) {
				throw new Exception($query->errorCode());
			}
		} catch(PDOException $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function nextId(string $table)
	{
		
		$query = $this->connection->prepare("SELECT COUNT(*) FROM $this->base.".$table);

		if ($query->execute()) {
			$nb = $query->fetch(PDO::FETCH_ASSOC)["COUNT(*)"];
			return $nb+1;
		} else {
			throw new Exception($query->errorCode());
		}
	}

	public function rawExecute(string $sql, array $parameters = []): PDOStatement
	{
		$query = $this->connection->prepare($sql);
		if ($query->execute($parameters)) {
			return $query;
		} else {
			throw new Exception($query->errorCode());
		}
	}
}
