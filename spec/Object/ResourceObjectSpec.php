<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Object;

use Slick\JSONAPI\Object\Links;
use Slick\JSONAPI\Object\Meta;
use Slick\JSONAPI\Object\Relationships;
use Slick\JSONAPI\Object\Resource;
use Slick\JSONAPI\Object\ResourceIdentifier;
use Slick\JSONAPI\Object\ResourceObject;
use PhpSpec\ObjectBehavior;

/**
 * ResourceObjectSpec specs
 *
 * @package spec\Slick\JSONAPI\Object
 */
class ResourceObjectSpec extends ObjectBehavior
{

    private $resourceIdentifier;
    private $type;
    private $identifier;
    private $attributes;
    private $links;
    private $meta;
    private $relationships;

    function let()
    {
        $this->type = 'people';
        $this->identifier = '1';
        $this->resourceIdentifier = new ResourceIdentifier($this->type, $this->identifier);
        $this->attributes = ['foo' => 'bar'];
        $this->links = new Links();
        $this->links->add('self', '/people/1');
        $this->meta = new Meta(['foo' => 'bar']);
        $this->relationships = new Relationships();
        $this->beConstructedWith($this->resourceIdentifier, $this->attributes, $this->relationships, $this->links, $this->meta);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ResourceObject::class);
    }

    function its_a_resource()
    {
        $this->shouldBeAnInstanceOf(Resource::class);
        $this->resourceIdentifier()->shouldBe($this->resourceIdentifier);
    }

    function it_has_a_type()
    {
        $this->type()->shouldBe($this->type);
    }

    function it_has_a_identifier()
    {
        $this->identifier()->shouldBe($this->identifier);
    }

    function it_has_a_attributes()
    {
        $this->attributes()->shouldBe($this->attributes);
    }

    function it_has_a_relationships()
    {
        $this->relationships()->shouldBe($this->relationships);
    }

    function it_has_a_links()
    {
        $this->links()->shouldBe($this->links);
    }

    function it_has_a_meta()
    {
        $this->meta()->shouldBe($this->meta);
    }

    function it_can_be_created_with_only_a_resource_identifier()
    {
        $this->beConstructedWith($this->resourceIdentifier);
        $this->attributes()->shouldBe(null);
        $this->links()->shouldBe(null);
        $this->meta()->shouldBe(null);
    }

    function it_can_change_is_attributes()
    {
        $attributes = ['bar' => 'baz'];
        $copy = $this->withAttributes($attributes);
        $copy->shouldBeAnInstanceOf(ResourceObject::class);
        $copy->shouldNotBe($this->getWrappedObject());
        $copy->attributes()->shouldNotBe($this->attributes);
        $copy->attributes()->shouldBe($attributes);
        $this->attributes()->shouldBe($this->attributes);
    }

    function it_can_change_one_attribute()
    {
        $resultingAttributes = array_merge($this->attributes, ['bar' => 'baz']);
        $copy = $this->withAttribute('bar', 'baz');
        $copy->shouldBeAnInstanceOf(ResourceObject::class);
        $copy->shouldNotBe($this->getWrappedObject());
        $copy->attributes()->shouldNotBe($this->attributes);
        $copy->attributes()->shouldBe($resultingAttributes);
        $this->attributes()->shouldBe($this->attributes);
    }

    function it_can_change_its_links()
    {
        $links = new Links();
        $copy = $this->withLinks($links);
        $copy->shouldBeAnInstanceOf(ResourceObject::class);
        $copy->shouldNotBe($this->getWrappedObject());
        $copy->links()->shouldNotBe($this->links);
        $copy->links()->shouldBe($links);
        $this->links()->shouldBe($this->links);
    }

    function it_can_change_its_meta()
    {
        $meta = new Meta(['foo' => 'bar']);
        $copy = $this->withMeta($meta);
        $copy->shouldBeAnInstanceOf(ResourceObject::class);
        $copy->shouldNotBe($this->getWrappedObject());
        $copy->meta()->shouldNotBe($this->meta);
        $copy->meta()->shouldBe($meta);
        $this->meta()->shouldBe($this->meta);
    }

    function it_can_change_its_relationships()
    {
        $relationships = new Relationships();
        $copy = $this->withRelationships($relationships);
        $copy->shouldBeAnInstanceOf(ResourceObject::class);
        $copy->shouldNotBe($this->getWrappedObject());
        $copy->relationships()->shouldNotBe($this->relationships);
        $copy->relationships()->shouldBe($relationships);
        $this->relationships()->shouldBe($this->relationships);
    }

    function it_can_be_converted_to_json()
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            'type' => $this->type,
            'id' => $this->identifier,
            'attributes' => $this->attributes,
            'relationships' => $this->relationships,
            'links' => $this->links,
            'meta' => $this->meta
        ]);
    }
}