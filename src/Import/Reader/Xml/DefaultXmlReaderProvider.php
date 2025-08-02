<?php

declare(strict_types=1);

namespace App\Import\Reader\Xml;

use XMLReader;

class DefaultXmlReaderProvider implements XmlReaderProviderInterface
{
    public function getReader(string $source): XMLReader
    {
        $xmlReader = new XMLReader();
        $xmlReader->open($source);

        return $xmlReader;
    }
}
