<?php

declare(strict_types=1);

namespace App\Import\Resolver;

use function strtolower;
use function preg_replace;
use function trim;

class ExternalIdResolver
{
    public function resolveExternalId(string $zipCode, string $address): string
    {
        $value = $zipCode . '_' . $address;
        $value = $this->removeDiacritics($value);
        $value = strtolower($value);
        $value = $this->replaceNotAllowedCharacters($value);

        return trim($value, '-');
    }

    private function removeDiacritics(string $value): string
    {
        return iconv('UTF-8', 'ASCII//TRANSLIT', $value);
    }

    private function replaceNotAllowedCharacters(string $value): string
    {
        return preg_replace('/[^\p{L}\p{Nd}]+/u', '-', $value);
    }
}
