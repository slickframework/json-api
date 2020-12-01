<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Object\Relationships;

use Slick\JSONAPI\Exception\SpecificationViolation;
use Slick\JSONAPI\Object\Links;
use Slick\JSONAPI\Object\Meta;
use Slick\JSONAPI\Object\Relationship;
use Slick\JSONAPI\Object\Relationships\ToOneRelationship;
use PhpSpec\ObjectBehavior;
use Slick\JSONAPI\Object\Resource;
use Slick\JSONAPI\Object\ResourceIdentifier;

/**
 * ToOneRelationshipSpec specs
 *
 * @package spec\Slick\JSONAPI\Object\Relationships
 */
class ToOneRelationshipSpec extends ObjectBehavior
{
    /** @var Resource */
    private $data;
    private $links;
    private $meta;

    function let()
    {
        $this->links = new Links();
        $this->links->add('self', '/articles/2');
        $this->data = new ResourceIdentifier('people', '123123-123123123-123123-12312');
        $this->meta = new Meta(['foo' => 'bar']);
        $this->beConstructedWith($this->data, $this->links, $this->meta);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ToOneRelationship::class);
    }

    function its_a_relationship()
    {
        $this->shouldBeAnInstanceOf(Relationship::class);
    }

    function it_has_a_data()
    {
        $this->data()->shouldBe($this->data);
    }

    function it_has_a_links()
    {
        $this->links()->shouldBe($this->links);
    }

    function it_has_a_meta()
    {
        $this->meta()->shouldBe($this->meta);
    }

    function it_can_be_converted_to_json()
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            'links' => $this->links,
            'data' => $this->data->resourceIdentifier(),
            'meta' => $this->meta
        ]);
    }

    function it_can_be_a_null_resource_object()
    {
        $this->beConstructedWith();
        $this->jsonSerialize()->shouldBe(['data' => null]);
    }
}