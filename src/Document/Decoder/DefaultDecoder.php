<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Document\Decoder;

use Slick\JSONAPI\Document;
use Slick\JSONAPI\Document\DocumentDecoder;
use Slick\JSONAPI\Object\SchemaDiscover;
use Slick\JSONAPI\Validator;

/**
 * DefaultDecoder
 *
 * @package Slick\JSONAPI\Document\Decoder
 */
final class DefaultDecoder implements DocumentDecoder
{
    /**
     * @var SchemaDiscover
     */
    private $schemaDiscover;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var Document
     */
    private $document;


    /**
     * Creates a DefaultDecoder
     *
     * @param SchemaDiscover $schemaDiscover
     * @param Validator $validator
     */
    public function __construct(SchemaDiscover $schemaDiscover, Validator $validator)
    {
        $this->schemaDiscover = $schemaDiscover;
        $this->validator = $validator;
    }


    /**
     * @inheritDoc
     */
    public function decodeTo(string $objectClassName)
    {
        $schema = $this->schemaDiscover->discover($objectClassName);
        return $schema->from($this->document->data());
    }

    /**
     * @inheritDoc
     */
    public function setRequestedDocument(Document $document): DocumentDecoder
    {
        $this->document = $document;
        return $this;
    }
}
