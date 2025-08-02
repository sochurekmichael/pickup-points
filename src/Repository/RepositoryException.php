<?php

declare(strict_types=1);

namespace App\Repository;

use Exception;

class RepositoryException extends Exception
{
    public static function byCountColumnsNotMatch(string $repositoryClass): self
    {
        return new self(sprintf('The number of columns in the %s does not match', $repositoryClass));
    }
}
