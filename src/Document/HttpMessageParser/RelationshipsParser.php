<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Document\HttpMessageParser;

use Slick\JSONAPI\Document\HttpMessageParser\Relationships\ToManyRelationshipParser;
use Slick\JSONAPI\Document\HttpMessageParser\Relationships\ToOneRelationshipParser;
use Slick\JSONAPI\Object\Relationships;

/**
 * RelationshipsParser
 *
 * @package Slick\JSONAPI\Document\HttpMessageParser
 */
final class RelationshipsParser
{
    /**
     * @var object
     */
    private $relationshipsData;

    /**
     * Creates a RelationshipsParser
     *
     * @param object $relationshipsData
     */
    public function __construct(object $relationshipsData)
    {
        $this->relationshipsData = $relationshipsData;
    }

    /**
     * Parses data relationships
     *
     * @return Relationships
     */
    public function parse(): Relationships
    {
        $relationships = new Relationships();
        foreach ($this->relationshipsData as $name => $relationshipDatum) {
            $prefix = "/data/relationships/{$name}";
            $relationships->add(
                $name,
                is_object($relationshipDatum->data)
                    ? (new ToOneRelationshipParser($relationshipDatum, $prefix))->parse()
                    : (new ToManyRelationshipParser($relationshipDatum, $prefix))->parse()
            );
        }
        return $relationships;
    }
}
