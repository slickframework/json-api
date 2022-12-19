<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Object\SchemaDiscover\Attributes;

use Slick\JSONAPI\Object\SchemaDiscover\Attributes\ResourceIdentifier;
use PhpSpec\ObjectBehavior;

/**
 * ResourceIdentifierSpec specs
 *
 * @package spec\Slick\JSONAPI\Object\SchemaDiscover\Attributes
 */
class ResourceIdentifierSpec extends ObjectBehavior
{
    private $type;

    function let()
    {
        $this->type = 'users';
        $this->beConstructedWith($this->type);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ResourceIdentifier::class);
    }

    function it_has_a_type()
    {
        $this->type()->shouldBe($this->type);
    }

    function it_can_retrieve_the_value_of_a_given_property()
    {
        $property = (new \ReflectionClass(ExampleResource::class))->getProperty('name');
        $this->withProperty($property);
        $this->retrieveValue(new ExampleResource())->shouldBe('Example');
    }
}