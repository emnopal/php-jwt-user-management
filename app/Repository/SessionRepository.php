<?php

namespace BadHabit\LoginManagement\Repository;

use BadHabit\LoginManagement\Domain\Session;

class SessionRepository
{

    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Session $session): Session
    {
        $sql = "INSERT INTO sessions(id, user_id) VALUES(?,?)";
        $statement = $this->connection->prepare($sql);
        $statement->execute([$session->id, $session->user_id]);
        return $session;
    }

    public function findById(string $id): ?Session
    {
        $sql = "SELECT id, user_id FROM sessions WHERE id = ?";
        $statement = $this->connection->prepare($sql);
        $statement->execute([$id]);
        try{
            if($row = $statement->fetch()){
                $session = new Session();
                $session->id = $row['id'];
                $session->user_id = $row['user_id'];
                return $session;
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function deleteById(string $id): void
    {
        $sql = "DELETE FROM sessions WHERE id = ?";
        $statement = $this->connection->prepare($sql);
        $statement->execute([$id]);
    }

    public function deleteAll(): void
    {
        $sql = "DELETE FROM sessions";
        $statement = $this->connection->prepare($sql);
        $statement->execute();
    }
}