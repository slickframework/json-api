<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Document\Encoder;

use Prophecy\Argument;
use Prophecy\Exception\Prediction\FailedPredictionException;
use Slick\JSONAPI\Document;
use Slick\JSONAPI\Document\DocumentConverter;
use Slick\JSONAPI\Document\DocumentEncoder;
use Slick\JSONAPI\Document\DocumentFactory;
use Slick\JSONAPI\Document\Encoder\DefaultEncoder;
use PhpSpec\ObjectBehavior;
use Slick\JSONAPI\JsonApi;
use Slick\JSONAPI\Object\Links;
use Slick\JSONAPI\Object\Meta;
use Slick\JSONAPI\Object\ResourceIdentifier;
use Slick\JSONAPI\Object\ResourceSchema;
use Slick\JSONAPI\Object\SchemaDiscover;

/**
 * DefaultEncoderSpec specs
 *
 * @package spec\Slick\JSONAPI\Document\Encoder
 */
class DefaultEncoderSpec extends ObjectBehavior
{
    function let(SchemaDiscover $discoverService, DocumentFactory $factory, DocumentConverter $converter)
    {
        $this->beConstructedWith($discoverService, $factory, $converter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DefaultEncoder::class);
    }

    function its_a_document_encoder()
    {
        $this->shouldBeAnInstanceOf(DocumentEncoder::class);
    }

    function it_has_a_discover_service(SchemaDiscover $discoverService)
    {
        $this->schemaDiscover()->shouldBe($discoverService);
    }

    function it_can_change_change_its_json_api_member(DocumentFactory $factory)
    {
        $jsonApi = new JsonApi(JsonApi::JSON_API_11);
        $factory->withJsonapi($jsonApi)->shouldBeCalledOnce();
        $this->withJsonapi($jsonApi)->shouldBe($this->getWrappedObject());
    }

    function it_can_change_meta_information(DocumentFactory $factory)
    {
        $meta = new Meta(['foo' => 'bar']);
        $factory->withMeta($meta)->shouldBeCalledOnce();
        $this->withMeta($meta)->shouldBe($this->getWrappedObject());
    }

    function it_can_change_its_links(DocumentFactory $factory)
    {
        $links = new Links();
        $links->add('self', '/articles/1');
        $factory->withLinks($links)->shouldBeCalledOnce();
        $this->withLinks($links)->shouldBe($this->getWrappedObject());
    }

    function it_can_change_link_prefix(DocumentFactory $factory)
    {
        $prefix = 'https://example.com';
        $factory->withLinkPrefix($prefix)->shouldBeCalledOnce();
        $this->withLinkPrefix($prefix)->shouldBe($this->getWrappedObject());
    }

    function it_can_encode_a_given_object(
        SchemaDiscover $discoverService,
        DocumentFactory $factory,
        ResourceSchema $schema,
        Document $document,
        DocumentConverter $converter
    ) {
        $object = (object)['foo' => 'bar'];
        $discoverService->discover($object)->willReturn($schema);
        $factory->createDocument($schema, $object)->willReturn($document);
        $converted = '{"foo": "bar"}';
        $converter->convert($document)->willReturn($converted);
        $this->encode($object)->shouldBe($converted);
    }

    function it_can_convert_documents(
        Document $document,
        DocumentConverter $converter,
        SchemaDiscover $discoverService,
        DocumentFactory $factory
    ) {
        $factory->createDocument(Argument::any(), Argument::any())->shouldNotBeCalled();
        $discoverService->discover(Argument::any())->shouldNotBeCalled();
        $converted = '{"foo": "bar"}';
        $document->data()->willReturn(new ResourceIdentifier('test', '2'));
        $converter->convert($document)->willReturn($converted);
        $this->encode($document)->shouldBe($converted);
    }

    function it_adds_general_meta_data_from_encoder(DocumentFactory $factory, DocumentConverter $converter)
    {
        $jsonApi = new JsonApi(JsonApi::JSON_API_11);
        $object = new Document\ResourceDocument(new ResourceIdentifier('test', '1'));

        $factory->withJsonapi($jsonApi)->shouldBeCalledOnce();
        $this->withJsonapi($jsonApi);

        /** @var Document\ResourceDocument $document */
        $document = Argument::that(function (Document\ResourceDocument $doc) use ($jsonApi) {
            if ($doc->jsonapi() !== $jsonApi) {
                throw new FailedPredictionException("Did not change the API version set.");
            }
            return true;
        });
        $converter->convert($document)->willReturn("");

        $this->encode($object);

    }
}