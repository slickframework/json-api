<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Object;

use Slick\JSONAPI\Object\Resource;
use Slick\JSONAPI\Object\ResourceIdentifier;
use PhpSpec\ObjectBehavior;

/**
 * ResourceIdentifierSpec specs
 *
 * @package spec\Slick\JSONAPI\Object
 */
class ResourceIdentifierSpec extends ObjectBehavior
{

    private $type;
    private $identifier;

    function let()
    {
        $this->type = 'articles';
        $this->identifier = '123123-234234234-234234-2342';
        $this->beConstructedWith($this->type, $this->identifier);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ResourceIdentifier::class);
    }

    function its_a_resource()
    {
        $this->shouldBeAnInstanceOf(Resource::class);
        $this->resourceIdentifier()->shouldBe($this->getWrappedObject());
    }

    function it_has_a_type()
    {
        $this->type()->shouldBe($this->type);
    }

    function it_has_a_identifier()
    {
        $this->identifier()->shouldBe($this->identifier);
    }

    function it_can_be_created_without_id()
    {
        $this->beConstructedWith($this->type);
        $this->shouldHaveType(ResourceIdentifier::class);
        $this->identifier()->shouldBe(null);
    }

    function it_can_be_converted_to_json()
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            'type' => $this->type,
            'id' => $this->identifier
        ]);
    }

    function it_can_be_a_local_identifier_when_requesting_new_resources()
    {
        $this->beConstructedThrough(
            'localIdentifier',
            [$this->type, $this->identifier]
        );
        $this->type()->shouldBe($this->type);
        $this->identifier()->shouldBe($this->identifier);
        $this->jsonSerialize()->shouldBe([
            'type' => $this->type,
            'lid' => $this->identifier
        ]);
    }
}