<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Object;

use IteratorAggregate;
use Slick\JSONAPI\Object\Relationship;
use Slick\JSONAPI\Object\Relationships;
use PhpSpec\ObjectBehavior;

/**
 * RelationshipsSpec specs
 *
 * @package spec\Slick\JSONAPI\Object
 */
class RelationshipsSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Relationships::class);
    }

    function its_a_relations_iterator_aggregate()
    {
        $this->shouldBeAnInstanceOf(IteratorAggregate::class);
    }

    function it_can_add_relations(Relationship $relationship)
    {
        $this->add('author', $relationship)->shouldBe($this->getWrappedObject());
    }

    function it_can_be_converted_to_json(Relationship $relationship)
    {
        $this->add('author', $relationship);
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            'author' => $relationship
        ]);
    }
}
