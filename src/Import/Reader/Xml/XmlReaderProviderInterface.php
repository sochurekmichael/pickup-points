<?php

declare(strict_types=1);

namespace App\Import\Reader\Xml;

use XMLReader;

interface XmlReaderProviderInterface
{
    public function getReader(string $source): XMLReader;
}
