<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Document;

use Slick\JSONAPI\Document;

/**
 * DocumentConverter
 *
 * @package Slick\JSONAPI\Document
 */
interface DocumentConverter
{

    /**
     * Converts JSON:API document into a JSON output string
     *
     * @param Document $document
     * @return string
     */
    public function convert(Document $document): string;
}
