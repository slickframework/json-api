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
interface Document extends MetaAwareObject, LinksAwareObject, JsonSerializable
{

    /**
     * jsonapi describing the server’s implementation
     *
     * @return JsonApi|null
     */
    public function jsonapi(): ?JsonApi;

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
     * Resource data member
     *
     * @return Resource|Resource[]|array|null
     */
    public function data();
}
