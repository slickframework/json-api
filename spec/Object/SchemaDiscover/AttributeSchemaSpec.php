<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Object\SchemaDiscover;

use Slick\JSONAPI\Object\ResourceObject;
use Slick\JSONAPI\Object\ResourceSchema;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\AsResourceObject;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\Relationship;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\RelationshipIdentifier;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\ResourceAttribute;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\ResourceIdentifier;
use Slick\JSONAPI\Object\SchemaDiscover\AttributeSchema;
use PhpSpec\ObjectBehavior;
use Slick\JSONAPI\Object\SchemaDiscover\DecodableAttribute;
use Slick\JSONAPI\Object\SchemaDiscover\EncodableAttribute;
use Slick\JSONAPI\Validator\SchemaDecodeValidator;

/**
 * AttributeSchemaSpec specs
 *
 * @package spec\Slick\JSONAPI\Object\SchemaDiscover
 */
class AttributeSchemaSpec extends ObjectBehavior
{

    function let(
        AsResourceObject $asResourceObject
    ) {
        $this->beConstructedWith($asResourceObject);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AttributeSchema::class);
    }

    function its_a_resource_schema()
    {
        $this->shouldBeAnInstanceOf(ResourceSchema::class);
    }

    function it_has_a_compound_flag(AsResourceObject $asResourceObject)
    {
        $asResourceObject->isCompound()->shouldBeCalled()->willReturn(true);
        $this->isCompound()->shouldBe(true);
    }

    function it_has_a_type(AsResourceObject $asResourceObject)
    {
        $type = 'tests';
        $asResourceObject->type()->shouldBeCalled()->willReturn($type);
        $this->type((object)[])->shouldBe($type);
    }

    function it_returns_resource_identifier_type_if_present(
        AsResourceObject $asResourceObject,
        ResourceIdentifier $identifier
    ) {
        $type = 'other_tests';
        $identifier->type()->shouldBeCalled()->willReturn($type);
        $asResourceObject->type()->shouldNotBeCalled();
        $this->beConstructedWith($asResourceObject, $identifier);
        $this->type((object)[])->shouldBe($type);
    }

    function it_generates_an_uuid_when_no_identifier_is_given(AsResourceObject $asResourceObject)
    {
        $asResourceObject->generateIdentifier()->shouldBeCalled()->willReturn(true);
        $regex = '/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i';
        $this->identifier((object)[])->shouldMatch($regex);
    }

    function it_returns_the_identifier_from_object(
        AsResourceObject $asResourceObject,
        ResourceIdentifier $identifier
    ) {
        $id = 'some-id';
        $encodedObject = (object)[];
        $identifier->retrieveValue($encodedObject)->shouldBeCalled()->willReturn($id);
        $asResourceObject->generateIdentifier()->shouldNotBeCalled();
        $this->beConstructedWith($asResourceObject, $identifier);
        $this->identifier($encodedObject)->shouldBe($id);
    }

    function it_returns_the_list_of_attributes(
        AsResourceObject $asResourceObject,
        EncodableAttribute $attribute
    ) {
        $value = 'bar';
        $property = 'foo';
        $encodedObject = (object)[];
        $attribute->retrieveValue($encodedObject)->shouldBeCalled()->willReturn($value);
        $this->beConstructedWith($asResourceObject, null, [$property => $attribute]);

        $this->attributes($encodedObject)->shouldBeLike([$property => $value]);
    }

    function it_can_retrieve_relationships(
        AsResourceObject $asResourceObject,
        Relationship $relationship
    ) {
        $value = 'test-value';
        $meta = ['foo' => 'bar'];
        $encodedObject = (object)[];
        $relationship->type()->willReturn(Relationship::TO_ONE);
        $relationship->retrieveValue($encodedObject)->willReturn($value);
        $relationship->links()->willReturn(null);
        $relationship->meta()->willReturn($meta);
        $this->beConstructedWith($asResourceObject, null, [], ['some' => $relationship]);

        $this->relationships($encodedObject)->shouldBeLike([
            'some' => [
                'data' => $value,
                'meta' => $meta
            ]
        ]);
    }


    function it_returns_empty_relation_when_object_property_does_not_exists(
        AsResourceObject $asResourceObject,
        Relationship $relationship
    ) {
        $encodedObject = (object)[];
        $relationship->type()->willReturn(Relationship::TO_ONE);
        $relationship->retrieveValue($encodedObject)->willReturn(null);

        $this->beConstructedWith($asResourceObject, null, [], ['some' => $relationship]);

        $this->relationships($encodedObject)->shouldBeLike([]);
    }

    function it_can_have_links_data(
        AsResourceObject $asResourceObject
    ) {
        $links = ['foo' => 'bar'];
        $asResourceObject->links()->shouldBeCalled()->willReturn($links);
        $this->links((object)[])->shouldBe($links);
    }

    function it_can_have_meta_data(
        AsResourceObject $asResourceObject
    ) {
        $meta = ['foo' => 'bar'];
        $asResourceObject->meta()->shouldBeCalled()->willReturn($meta);
        $this->meta((object)[])->shouldBe($meta);
    }

    function it_can_create_and_assign_attributes_from_an_api_document(
        AsResourceObject $asResourceObject,
        ResourceAttribute $attribute,
        ResourceIdentifier $identifier,
        RelationshipIdentifier $relationshipIdentifier,
        ResourceObject $object
    ) {
        $encodedObject = (object)['foo' => null, 'id' => 0];
        $asResourceObject->createInstance()->willReturn($encodedObject);

        $attribute->assignValue($encodedObject, $object)->shouldBeCalled();
        $relationshipIdentifier->assignValue($encodedObject, $object)->shouldBeCalled();
        $identifier->assignValue($encodedObject, $object)->shouldBeCalled();

        $this->beConstructedWith(
            $asResourceObject,
            $identifier,
            ['foo' => $attribute], null,
            ['id' => $relationshipIdentifier]
        );

        $this->from($object)->shouldBe($encodedObject);
    }

    function it_can_validate_a_json_api_document_values(
        AsResourceObject $asResourceObject,
        ResourceAttribute $attribute,
        ResourceIdentifier $identifier,
        RelationshipIdentifier $relationshipIdentifier,
        ResourceObject $object,
        SchemaDecodeValidator $validator
    ) {
        $attribute->validate($object, $validator)->shouldBeCalled();
        $relationshipIdentifier->validate($object, $validator)->shouldBeCalled();
        $identifier->validate($object, $validator)->shouldBeCalled();

        $this->beConstructedWith(
            $asResourceObject,
            $identifier,
            ['foo' => $attribute], null,
            ['id' => $relationshipIdentifier]
        );

        $this->validate($object, $validator);
    }
}