<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Document\HttpMessageParser;

use Slick\JSONAPI\Object\ResourceIdentifier;
use Slick\JSONAPI\Object\ResourceObject;

/**
 * ResourceObjectParser
 *
 * @package Slick\JSONAPI\Document\HttpMessageParser
 */
final class ResourceObjectParser
{
    /**
     * @var object
     */
    private $resourceData;

    /**
     * Creates a ResourceObjectParser
     *
     * @param object $resourceData
     */
    public function __construct(object $resourceData)
    {
        $this->resourceData = $resourceData;
    }

    public function parse(): ResourceObject
    {
        $resource = new ResourceObject(
            $this->parseResourceIdentifier(),
            $this->parseAttributes()
        );

        $resource = LinksMemberParser::parse($this->resourceData, $resource);
        $resource = MetaMemberParser::parse($this->resourceData, $resource);

        $relationships = DocumentParser::getProperty($this->resourceData, 'relationships');
        if ($relationships) {
            $resource = $resource->withRelationships(
                (new RelationshipsParser($relationships))->parse()
            );
        }

        return $resource;
    }

    /**
     * parseResourceIdentifier
     *
     * @return ResourceIdentifier
     */
    private function parseResourceIdentifier(): ResourceIdentifier
    {
        return new ResourceIdentifier(
            DocumentParser::getProperty($this->resourceData, 'type'),
            DocumentParser::getProperty($this->resourceData, "id")
        );
    }

    /**
     * Parses Attributes
     *
     * @return array|null
     */
    private function parseAttributes(): ?array
    {
        $attributes = DocumentParser::getProperty($this->resourceData, "attributes");
        if (!$attributes) {
            return null;
        }

        $data = [];
        foreach ($attributes as $name => $attribute) {
            $data[$name] = $attribute;
        }

        return empty($data) ? null : $data;
    }
}
