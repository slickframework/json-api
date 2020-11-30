<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Document\Converted;

use Slick\JSONAPI\Document;
use Slick\JSONAPI\Document\DocumentConverter;

/**
 * PHPJson
 *
 * @package Slick\JSONAPI\Document\Converted
 */
final class PHPJson implements DocumentConverter
{

    /**
     * @inheritDoc
     */
    public function convert(Document $document): string
    {
        return json_encode(
            $document,
            JSON_INVALID_UTF8_IGNORE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES
        );
    }
}
