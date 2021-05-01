<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Document;

use Slick\JSONAPI\Document;
use Slick\JSONAPI\Document\Decoder\DefaultDecoder;

/**
 * DocumentDecoder
 *
 * @package Slick\JSONAPI\Document
 */
interface DocumentDecoder
{

    /**
     * Decodes request JSON:API document into an object of the provided type
     *
     * @param string $objectClassName
     * @return mixed
     */
    public function decodeTo(string $objectClassName);

    /**
     * Sets the HTTP requested with JSON:API document content
     *
     * @param Document $document
     * @return DefaultDecoder
     */
    public function setRequestedDocument(Document $document): DocumentDecoder;
}
