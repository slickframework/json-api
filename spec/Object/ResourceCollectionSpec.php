<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Object;

use IteratorAggregate;
use Slick\JSONAPI\Object\Resource;
use Slick\JSONAPI\Object\ResourceCollection;
use PhpSpec\ObjectBehavior;
use Slick\JSONAPI\Object\ResourceIdentifier;

/**
 * ResourceCollectionSpec specs
 *
 * @package spec\Slick\JSONAPI\Object
 */
class ResourceCollectionSpec extends ObjectBehavior
{

    private $type;

    function let(Resource $someResource)
    {
        $this->type = 'resourceType';
        $this->beConstructedWith($this->type, [$someResource]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ResourceCollection::class);
    }

    function its_a_resource_iterator_aggregate()
    {
        $this->shouldBeAnInstanceOf(IteratorAggregate::class);
    }

    function it_can_add_resources(Resource $resource)
    {
        $this->add($resource)->shouldBeAnInstanceOf($this->getWrappedObject());
        $this->isEmpty()->shouldBe(false);
    }

    function it_has_an_empty_flag()
    {
        $this->beConstructedWith($this->type);
        $this->isEmpty()->shouldBe(true);
    }

    function its_a_resource()
    {
        $this->shouldBeAnInstanceOf(Resource::class);
    }

    function it_has_a_type()
    {
        $this->type()->shouldBe($this->type);
    }

    function it_has_a_identifier()
    {
        $this->identifier()->shouldBe(null);
    }

    function it_as_a_resource_identifier()
    {
        $rId = $this->resourceIdentifier();
        $rId->shouldBeAnInstanceOf(ResourceIdentifier::class);
        $rId->type()->shouldBe($this->type);
    }

    function it_can_be_converted_to_json(Resource $someResource)
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            $someResource
        ]);
    }
}