<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Document\HttpMessageParser\Relationships;

use Slick\JSONAPI\Document\HttpMessageParser\Relationships\ToManyRelationshipParser;
use PhpSpec\ObjectBehavior;
use Slick\JSONAPI\Object\Relationships\ToManyRelationship;

/**
 * ToManyRelationshipParserSpec specs
 *
 * @package spec\Slick\JSONAPI\Document\HttpMessageParser\Relationships
 */
class ToManyRelationshipParserSpec extends ObjectBehavior
{

    private $rolesData;

    function let()
    {
        $file = dirname(dirname(__DIR__)).'/example.json';
        $this->rolesData = json_decode(file_get_contents($file))->data->relationships->roles;
        $this->beConstructedWith($this->rolesData, "/data/relationships/roles");
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ToManyRelationshipParser::class);
    }

    function it_can_parse_to_many_relationships()
    {
        $relation = $this->parse();
        $relation->shouldBeAnInstanceOf(ToManyRelationship::class);
    }
}