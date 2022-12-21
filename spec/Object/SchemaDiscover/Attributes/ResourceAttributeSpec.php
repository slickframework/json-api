<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Object\SchemaDiscover\Attributes;

use PhpSpec\Exception\Example\FailureException;
use Prophecy\Argument;
use Slick\JSONAPI\Object\ErrorObject\ErrorSource;
use Slick\JSONAPI\Object\ResourceIdentifier;
use Slick\JSONAPI\Object\ResourceObject;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\ResourceAttribute;
use PhpSpec\ObjectBehavior;
use Slick\JSONAPI\Validator\SchemaDecodeValidator;

include(__DIR__.'/ValueObject.php');

/**
 * ResourceAttributeSpec specs
 *
 * @package spec\Slick\JSONAPI\Object\SchemaDiscover\Attributes
 */
class ResourceAttributeSpec extends ObjectBehavior
{

    private $name;

    function let()
    {
        $this->name = 'resourceName';
        $this->beConstructedWith($this->name);
    }


    function it_is_initializable()
    {
        $this->shouldHaveType(ResourceAttribute::class);
    }

    function it_can_retrieve_the_value_of_a_given_property()
    {
        $property = (new \ReflectionClass(ExampleResource::class))->getProperty('name');
        $this->withProperty($property);
        $this->retrieveValue(new ExampleResource())->shouldBe('Example');
    }

    function it_can_assign_a_value_to_an_object()
    {
        $object = new ExampleResource();
        $property = (new \ReflectionClass(ExampleResource::class))->getProperty('value');
        $this->withProperty($property);
        $value = "Test passes!";
        $this->assignValue($object, new ResourceObject(
            new ResourceIdentifier('example', '2'),
            [$this->name => $value]
        ));
        if ($object->value() !== $value) {
            throw new FailureException("Object don't have the expected value.");
        }
    }

    function it_can_validate_an_attribute_present_on_resource_object(SchemaDecodeValidator $validator)
    {
        $value = "Test passes!";
        $resourceObject = new ResourceObject(
            new ResourceIdentifier('example', '2'),
            ['other' => $value]
        );
        $this->validate($resourceObject, $validator);
        $validator->add(Argument::any())->shouldNotHaveBeenCalled();
    }

    function it_passes_validation_when_present_and_without_classname(SchemaDecodeValidator $validator)
    {
        $value = "Test passes!";
        $resourceObject = new ResourceObject(
            new ResourceIdentifier('example', '2'),
            [$this->name => $value]
        );
        $this->validate($resourceObject, $validator);
        $validator->add(Argument::any())->shouldNotHaveBeenCalled();
    }

    function it_fails_validation_when_required_and_not_present(SchemaDecodeValidator $validator)
    {
        $this->beConstructedWith($this->name, null, true);
        $value = "Test passes!";
        $resourceObject = new ResourceObject(
            new ResourceIdentifier('example', '2'),
            ['other' => $value]
        );

        $validator->add(
            "Missing '$this->name' attribute",
            "Attribute '$this->name' is mandatory, but it isn't present in the requested resource object.",
            Argument::type(ErrorSource::class),
            "400"
        )->shouldBeCalled()->willReturn($validator);
        $this->validate($resourceObject, $validator);
    }

    function it_passes_when_exists_and_have_a_classname(SchemaDecodeValidator $validator)
    {
        $this->beConstructedWith('valueObject', ValueObject::class);
        $value = "Test passes!";
        $resourceObject = new ResourceObject(
            new ResourceIdentifier('example', '2'),
            ['valueObject' => $value]
        );

        $this->validate($resourceObject, $validator);
        $validator->add(Argument::any())->shouldNotHaveBeenCalled();
    }

    function it_fails_when_exists_and_fails_to_create_object(SchemaDecodeValidator $validator)
    {
        $this->beConstructedWith('valueObject', ValueObject::class);
        $value = "Test fails!";
        $resourceObject = new ResourceObject(
            new ResourceIdentifier('example', '2'),
            ['valueObject' => $value]
        );

        $validator->add(
            "Attribute 'valueObject' is invalid",
            "Test fails!",
            Argument::type(ErrorSource::class),
            "400"
        )->shouldBeCalled()->willReturn($validator);

        $this->validate($resourceObject, $validator);
    }
}