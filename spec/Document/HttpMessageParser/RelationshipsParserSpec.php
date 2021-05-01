<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Document\HttpMessageParser;

use Slick\JSONAPI\Document\HttpMessageParser\RelationshipsParser;
use PhpSpec\ObjectBehavior;
use Slick\JSONAPI\Object\Relationships;
use Slick\JSONAPI\Object\ResourceObject;

/**
 * RelationshipsParserSpec specs
 *
 * @package spec\Slick\JSONAPI\Document\HttpMessageParser
 */
class RelationshipsParserSpec extends ObjectBehavior
{

    private $relationshipData;

    function let()
    {
        $file = dirname(__DIR__).'/example.json';
        $this->relationshipData = json_decode(file_get_contents($file))->data->relationships;
        $this->beConstructedWith($this->relationshipData);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RelationshipsParser::class);
    }

    function it_parses_relationships()
    {
        $relationShips = $this->parse();
        $relationShips->shouldBeAnInstanceOf(Relationships::class);
        $relationship = $relationShips->get('group');
        $relationship->shouldBeAnInstanceOf(Relationships\ToOneRelationship::class);
        $relationship->data()->shouldBeAnInstanceOf(ResourceObject::class);

    }
}