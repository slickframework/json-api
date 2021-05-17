<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Document;

use Psr\Http\Message\ServerRequestInterface;
use Slick\JSONAPI\Document;
use Slick\JSONAPI\Document\Factory\SparseFields;
use Slick\JSONAPI\JsonApi;
use Slick\JSONAPI\Object\Links;
use Slick\JSONAPI\Object\Meta;
use Slick\JSONAPI\Object\SchemaDiscover;

/**
 * DocumentEncoder
 *
 * @package Slick\JSONAPI\Document
 */
interface DocumentEncoder
{

    /**
     * Encodes provided object into a JSON string
     *
     * @param mixed $object
     * @return string
     */
    public function encode($object): string;

    /**
     * Schema discover used in this encoder
     *
     * @return SchemaDiscover
     */
    public function schemaDiscover(): SchemaDiscover;

    /**
     * Sets JSON:API object of the documents created by this factory
     *
     * @param JsonApi $jsonApi
     * @return DocumentEncoder
     */
    public function withJsonapi(JsonApi $jsonApi): DocumentEncoder;

    /**
     * Sets the Meta object of the documents created by this factory
     *
     * @param Meta $meta
     * @return DocumentEncoder
     */
    public function withMeta(Meta $meta): DocumentEncoder;

    /**
     * Sets the top level links of the documents created by this factory
     *
     * @param Links $links
     * @return DocumentEncoder
     */
    public function withLinks(Links $links): DocumentEncoder;

    /**
     * Sets the link prefix to be used in entire document
     *
     * @param string $linkPrefix
     * @return DocumentEncoder
     */
    public function withLinkPrefix(string $linkPrefix): DocumentEncoder;

    /**
     * Creates a JSON:API document for the given object
     *
     * @param mixed $object
     * @return Document
     */
    public function documentFor($object): Document;

    /**
     * Sets the request wrapper that allows sparse fields
     *
     * @param SparseFields $sparseFields
     * @return DocumentEncoder
     */
    public function withSparseFields(SparseFields $sparseFields): DocumentEncoder;
}
