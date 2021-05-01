<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Document\HttpMessageParser\Relationships;

use Slick\JSONAPI\Document\HttpMessageParser\Relationships\ToOneRelationshipParser;
use PhpSpec\ObjectBehavior;
use Slick\JSONAPI\Object\Relationships\ToOneRelationship;

/**
 * ToOneRelationshipParserSpec specs
 *
 * @package spec\Slick\JSONAPI\Document\HttpMessageParser\Relationships
 */
class ToOneRelationshipParserSpec extends ObjectBehavior
{

    private $relationshipData;

    function let()
    {
        $file = dirname(dirname(__DIR__)).'/example.json';
        $this->relationshipData = json_decode(file_get_contents($file))->data->relationships->group;
        $this->beConstructedWith($this->relationshipData, "/data/relationships/group");
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ToOneRelationshipParser::class);
    }

    function it_parses_to_one_relationships()
    {
        $relation = $this->parse();
        $relation->shouldBeAnInstanceOf(ToOneRelationship::class);
    }
}