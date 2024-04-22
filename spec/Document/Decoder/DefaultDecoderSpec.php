<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Document\Decoder;

use Prophecy\Argument;
use Slick\JSONAPI\Document;
use Slick\JSONAPI\Document\Decoder\DefaultDecoder;
use PhpSpec\ObjectBehavior;
use Slick\JSONAPI\Document\DocumentDecoder;
use Slick\JSONAPI\Exception\FailedValidation;
use Slick\JSONAPI\Object\ResourceIdentifier;
use Slick\JSONAPI\Object\ResourceObject;
use Slick\JSONAPI\Object\SchemaDiscover;
use Slick\JSONAPI\Validator;
use spec\Slick\JSONAPI\Document\Decoder\Fixtures\CreatePersonCommand;
use spec\Slick\JSONAPI\Document\Decoder\Fixtures\CreatePersonCommandSchema;
use spec\Slick\JSONAPI\Document\Decoder\Fixtures\User;
use spec\Slick\JSONAPI\Document\Decoder\Fixtures\Member;

/**
 * DefaultDecoderSpec specs
 *
 * @package spec\Slick\JSONAPI\Document\Decoder
 */
class DefaultDecoderSpec extends ObjectBehavior
{

    function let(SchemaDiscover $schemaDiscover, Validator\SchemaDecodeValidator $validator)
    {
        $validator->isValid(Argument::type(ResourceObject::class))->willReturn(true);
        $schemaDiscover->discover(CreatePersonCommand::class)->willReturn(new CreatePersonCommandSchema());
        $this->beConstructedWith($schemaDiscover, $validator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DefaultDecoder::class);
    }

    function its_a_document_decoder()
    {
        $this->shouldBeAnInstanceOf(DocumentDecoder::class);
    }

    function it_decodes_a_document(Document $document, ResourceObject $resource)
    {
        $document->data()->willReturn($resource);
        $resource->attributes()->willReturn(['name' => 'John Doe', 'email' => 'john.doe@example.com']);
        $this->setRequestedDocument($document);
        $command = $this->decodeTo(CreatePersonCommand::class);
        $command->shouldBeAnInstanceOf(CreatePersonCommand::class);
    }

    function it_throws_exception_when_validation_fails(
        Validator\SchemaDecodeValidator $validator,
        Document $document,
        ResourceObject $resource
    ) {
        $document->data()->willReturn($resource);
        $resource->attributes()->willReturn(['name' => 'John Doe', 'email' => 'john.doe@example.com']);
        $this->setRequestedDocument($document);
        $validator->isValid(Argument::type(ResourceObject::class))->willReturn(false);
        $validator->exception()->willReturn(new FailedValidation());

        $this->shouldThrow(FailedValidation::class)
            ->during('decodeTo', [CreatePersonCommand::class]);
    }

    function it_can_decode_an_object_with_attributes(Validator\SchemaDecodeValidator $validator)
    {
        $discover = new SchemaDiscover\AttributeSchemaDiscover();
        $this->beConstructedWith($discover, $validator);

        $json = dirname(__DIR__) . '/Encoder/member.json';
        $resourceData = json_decode(file_get_contents($json));
        $resourceObjectParser = new Document\HttpMessageParser\ResourceObjectParser($resourceData->data);
        $data = $resourceObjectParser->parse();
        $this->setRequestedDocument(new Document\ResourceDocument($data));
        $member = $this->decodeTo(Member::class);
        $member->name()->shouldBe($resourceData->data->attributes->name);
    }

    function it_can_decode_an_object_when_parent_class_has_attributes_set(
        Document $document,
        ResourceObject $resource,
        SchemaDiscover $schemaDiscover
    ) {

        $resource = new ResourceObject(
            resourceIdentifier: new ResourceIdentifier('users', '2'),
            attributes: ['name' => 'John Doe', 'email' => 'john.doe@example.com']
        );

        $document->data()->willReturn($resource);
        $this->setRequestedDocument($document);
        $resourceSchema = (new SchemaDiscover\AttributeSchemaDiscover())->discover(User::class);
        $schemaDiscover->discover(User::class)->willReturn($resourceSchema);
        $user = $this->decodeTo(User::class);
        $user->name()->shouldBe('John Doe');
        $user->email()->shouldBe('john.doe@example.com');
    }
}
