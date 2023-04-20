<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Document;

use Slick\JSONAPI\Document;
use Slick\JSONAPI\Document\MetaDocument;
use PhpSpec\ObjectBehavior;
use Slick\JSONAPI\JsonApi;
use Slick\JSONAPI\Object\Links;
use Slick\JSONAPI\Object\Meta;

/**
 * MetaDocumentSpec specs
 *
 * @package spec\Slick\JSONAPI\Document
 */
class MetaDocumentSpec extends ObjectBehavior
{

    private $meta;

    function let()
    {
        $this->meta = new Meta(['foo' => 'bar']);
        $this->beConstructedWith($this->meta);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(MetaDocument::class);
    }

    function it_has_a_json_api()
    {
        $this->jsonapi()->shouldBe(null);
    }

    function it_has_a_links()
    {
        $this->links()->shouldBe(null);
    }

    function it_has_an_included_resources()
    {
        $this->included()->shouldBe(null);
    }

    function its_a_document()
    {
        $this->shouldBeAnInstanceOf(Document::class);
    }

    function it_has_a_meta()
    {
        $this->meta()->shouldBe($this->meta);
    }

    function it_can_be_constructed_with_links_and_json_api()
    {
        $jsonApi = new JsonApi(JsonApi::JSON_API_11);
        $links = new Links();
        $this->beConstructedWith($this->meta, $jsonApi, $links);

        $this->jsonapi()->shouldBe($jsonApi);
        $this->links()->shouldBe($links);
    }

    function it_can_change_its_meta_object()
    {
        $meta = new Meta(['foo' => 'bar']);
        $copy = $this->withMeta($meta);
        $copy->shouldBeAnInstanceOf(Document::class);
        $copy->shouldNotBe($this->getWrappedObject());
        $this->meta()->shouldBe($this->meta);
        $copy->meta()->shouldBeLike($meta);
    }

    function it_can_change_its_links_object()
    {
        $links = new Links();
        $copy = $this->withLinks($links);
        $copy->shouldBeAnInstanceOf(Document::class);
        $copy->shouldNotBe($this->getWrappedObject());
        $this->links()->shouldBe(null);
        $copy->links()->shouldBeLike($links);
    }

    function it_can_change_its_jsonapi_object()
    {
        $jsonApi = new JsonApi();
        $copy = $this->withJsonapi($jsonApi);
        $copy->shouldBeAnInstanceOf(Document::class);
        $copy->shouldNotBe($this->getWrappedObject());
        $this->jsonapi()->shouldBe(null);
        $copy->jsonapi()->shouldBe($jsonApi);
    }

    function it_can_be_converted_to_json()
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            'meta' => $this->meta
        ]);
    }
}
