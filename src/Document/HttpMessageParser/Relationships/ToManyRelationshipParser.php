<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Document\HttpMessageParser\Relationships;

use Slick\JSONAPI\Document\HttpMessageParser\DocumentParser;
use Slick\JSONAPI\Document\HttpMessageParser\LinksMemberParser;
use Slick\JSONAPI\Document\HttpMessageParser\MetaMemberParser;
use Slick\JSONAPI\Document\HttpMessageParser\ResourceObjectParser;
use Slick\JSONAPI\Object\Relationships\ToManyRelationship;
use Slick\JSONAPI\Object\ResourceCollection;

/**
 * ToManyRelationshipParser
 *
 * @package Slick\JSONAPI\Document\HttpMessageParser\Relationships
 */
final class ToManyRelationshipParser
{
    /**
     * @var object
     */
    private $relationshipData;
    /**
     * @var string
     */
    private $prefix;


    /**
     * Creates a ToManyRelationshipParser
     *
     * @param object $relationshipData
     * @param string $prefix
     */
    public function __construct(object $relationshipData, string $prefix)
    {
        $this->relationshipData = $relationshipData;
        $this->prefix = $prefix;
    }

    public function parse(): ToManyRelationship
    {
        $data = DocumentParser::getMandatoryProperty($this->relationshipData, 'data', $this->prefix);
        $resources = [];
        foreach ($data as $datum) {
            $resources[] = (new ResourceObjectParser($datum))->parse();
        }
        $relationship = new ToManyRelationship(
            new ResourceCollection($resources[0]->type(), $resources)
        );

        $relationship = LinksMemberParser::parse($this->relationshipData, $relationship);
        $relationship = MetaMemberParser::parse($this->relationshipData, $relationship);

        return $relationship;
    }


}
