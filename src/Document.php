<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI;

use JsonSerializable;
use Slick\JSONAPI\Object\Links;
use Slick\JSONAPI\Object\Meta;
use Slick\JSONAPI\Object\Resource;

/**
 * Document
 *
 * @package Slick\JSONAPI
 */
interface Document extends JsonSerializable
{

    /**
     * jsonapi describing the server’s implementation
     *
     * @return JsonApi|null
     */
    public function jsonapi(): ?JsonApi;

    /**
     * A meta object that contains non-standard meta-information.
     *
     * @return Meta|null
     */
    public function meta(): ?Meta;

    /**
     * A links object related to the primary data.
     *
     * @return Links|null
     */
    public function links(): ?Links;

    /**
     * An array of resource objects that are related to the primary data and/or
     * each other (“included resources”).
     *
     * This list of resources should be retrieved from existing relationships and
     * should not be duplicated given the possibility of multiple relationships to
     * the same resource type.
     *
     * This method SHOULD only return resource list when the document is a Compound
     * document.
     *
     * @return Resource[]|null
     */
    public function included(): ?array;

    /**
     * Returns a new document with provided JSON:API object
     *
     * This method will ALWAYS return a new copy (clone) of the document
     * maintaining object immutability.
     *
     * @param JsonApi $jsonApi
     * @return Document
     */
    public function withJsonapi(JsonApi $jsonApi): Document;

    /**
     * Returns a new document with provided Meta object
     *
     * This method will ALWAYS return a new copy (clone) of the document
     * maintaining object immutability.
     *
     * @param Meta $meta
     * @return Document|self
     */
    public function withMeta(Meta $meta): Document;

    /**
     * Returns a new document with provided Links object
     *
     * This method will ALWAYS return a new copy (clone) of the document
     * maintaining object immutability.
     *
     * @param Links $links
     * @return Document
     */
    public function withLinks(Links $links): Document;
}
