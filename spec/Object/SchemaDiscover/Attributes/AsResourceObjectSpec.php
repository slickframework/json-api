<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Object\SchemaDiscover\Attributes;

use Slick\JSONAPI\Exception\DocumentEncoderFailure;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\AsResourceObject;
use PhpSpec\ObjectBehavior;


include(__DIR__.'/ExampleResource.php');

/**
 * AsResourceObjectSpec specs
 *
 * @package spec\Slick\JSONAPI\Object\SchemaDiscover\Attributes
 */
class AsResourceObjectSpec extends ObjectBehavior
{

    private $meta;
    private $links;
    private $schemaClass;
    private $isCompound;

    function let()
    {
        $this->meta = ['foo' => 'bar'];
        $this->links = [AsResourceObject::LINK_SELF];
        $this->schemaClass = \stdClass::class;
        $this->isCompound = true;
        $this->beConstructedWith(null, $this->meta, $this->links, $this->schemaClass, $this->isCompound);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AsResourceObject::class);
    }

    function it_has_a_meta()
    {
        $this->meta()->shouldBe($this->meta);
    }

    function it_has_a_links()
    {
        $this->links()->shouldBeLike([AsResourceObject::LINK_SELF => true]);
    }

    function it_has_a_schemaClass()
    {
        $this->schemaClass()->shouldBe($this->schemaClass);
    }

    function it_has_a_isCompound()
    {
        $this->isCompound()->shouldBe($this->isCompound);
    }

    function it_has_a_type()
    {
        $this->type()->shouldBe(null);
    }

    function it_can_determine_resource_type()
    {
        $this->withClass(new \ReflectionClass(AsResourceObjectSpec::class));
        $this->type()->shouldBe('as_resource_object_spec');
    }

    function it_uses_the_type_if_defined()
    {
        $type = "some_type";
        $this->beConstructedWith($type);
        $this->type()->shouldBe($type);
    }

    function it_can_create_an_object_of_a_given_reflection()
    {
        $this->withClass(new \ReflectionClass(ExampleResource::class));
        $this->createInstance()->shouldBeAnInstanceOf(ExampleResource::class);
    }

    function it_cannot_create_an_instance_without_a_class_reflection()
    {
        $this->shouldThrow(DocumentEncoderFailure::class)
            ->during('createInstance');
    }


}
