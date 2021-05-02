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
use Slick\JSONAPI\Validator\SchemaDecodeValidator;

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
     * @var SchemaDecodeValidator
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
     * @param SchemaDecodeValidator $validator
     */
    public function __construct(SchemaDiscover $schemaDiscover, SchemaDecodeValidator $validator)
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
        $resourceObject = $this->document->data();
        $schema->validate($resourceObject, $this->validator);
        if (!$this->validator->isValid($resourceObject)) {
            throw $this->validator->exception();
        }
        return $schema->from($resourceObject);
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
