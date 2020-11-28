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

/**
 * ResourceCollectionSpec specs
 *
 * @package spec\Slick\JSONAPI\Object
 */
class ResourceCollectionSpec extends ObjectBehavior
{

    function let(Resource $someResource)
    {
        $this->beConstructedWith([$someResource]);
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
        $this->beConstructedWith();
        $this->isEmpty()->shouldBe(true);
    }

}