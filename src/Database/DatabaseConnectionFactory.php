<?php

declare(strict_types=1);

namespace App\Database;

use PDO;

class DatabaseConnectionFactory
{
    public function create(string $dsn, string $username, string $password): PDO
    {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }
}
