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
use Slick\JSONAPI\Object\ResourceSchema;
use Slick\JSONAPI\Object\SchemaDiscover;

/**
 * DocumentFactory
 *
 * @package Slick\JSONAPI\Document
 */
interface DocumentFactory
{

    /**
     * Creates a document for provided resource schema
     *
     * @param ResourceSchema $schema
     * @param mixed $object
     * @return Document
     */
    public function createDocument(ResourceSchema $schema, $object): Document;

    /**
     * Sets schema discover
     *
     * @param SchemaDiscover $discover
     * @return DocumentFactory
     */
    public function withSchemaDiscover(SchemaDiscover $discover): DocumentFactory;

    /**
     * Sets JSON:API object of the documents created by this factory
     *
     * @param JsonApi $jsonApi
     * @return DocumentFactory
     */
    public function withJsonapi(JsonApi $jsonApi): DocumentFactory;

    /**
     * Sets the Meta object of the documents created by this factory
     *
     * @param Meta $meta
     * @return DocumentFactory
     */
    public function withMeta(Meta $meta): DocumentFactory;

    /**
     * Sets the top level links of the documents created by this factory
     *
     * @param Links $links
     * @return DocumentFactory
     */
    public function withLinks(Links $links): DocumentFactory;

    /**
     * Sets the link prefix to be used with resource linkage
     *
     * @param string $linkPrefix
     * @return DocumentFactory
     */
    public function withLinkPrefix(string $linkPrefix): DocumentFactory;

    /**
     * Sets the request wrapper that allows sparse fields
     *
     * @param SparseFields $sparseFields
     * @return DocumentFactory
     */
    public function withSparseFields(SparseFields $sparseFields): DocumentFactory;
}
