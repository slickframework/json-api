<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Document;

use PhpSpec\Wrapper\Subject;
use Slick\Http\Message\Request;
use Slick\Http\Message\Stream\FileStream;
use Slick\JSONAPI\Document\HttpMessageParser;
use PhpSpec\ObjectBehavior;
use Slick\JSONAPI\Document\ResourceDocument;
use Slick\JSONAPI\JsonApi;
use Slick\JSONAPI\Object\Links;
use Slick\JSONAPI\Object\Meta;
use Slick\JSONAPI\Object\ResourceObject;

/**
 * RequestParserSpec specs
 *
 * @package spec\Slick\JSONAPI\Document
 */
class HttpMessageParserSpec extends ObjectBehavior
{
    private $exampleFile;
    private $expectedValues;

    function let()
    {
        $this->exampleFile = __DIR__ . '/example.json';
        $this->expectedValues = json_decode(file_get_contents($this->exampleFile));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(HttpMessageParser::class);
    }

    function it_can_parse_json_api()
    {
        $request = $this->createRequest();
        /** @var Subject|ResourceDocument  $document */
        $document = $this->parse($request);
        $document->shouldBeAnInstanceOf(ResourceDocument::class);
        $document->data()->identifier()->shouldBe($this->expectedValues->data->id);
        $document->jsonapi()->shouldBeAnInstanceOf(JsonApi::class);
        $document->jsonapi()->meta()->get('copyright')->shouldBe("Example Inc. 2020");
    }

    function it_can_parse_document_meta_information()
    {
        $request = $this->createRequest();
        /** @var Subject|ResourceDocument  $document */
        $document = $this->parse($request);
        $document->meta()->shouldBeAnInstanceOf(Meta::class);
        $document->meta()->get('copyright')->shouldBe("Copyright 2015 Example Corp.");
    }

    function it_can_parse_document_links()
    {
        $request = $this->createRequest();
        /** @var Subject|ResourceDocument  $document */
        $document = $this->parse($request);
        $document->links()->shouldBeAnInstanceOf(Links::class);
    }

    function it_can_parse_a_resource_object()
    {
        $request = $this->createRequest();
        /** @var Subject|ResourceDocument  $document */
        $document = $this->parse($request);
        /** @var ResourceObject|Subject $resource*/
        $resource = $document->data();
        $resource->shouldBeAnInstanceOf(ResourceObject::class);
    }

    /**
     * createRequest
     *
     * @return Request
     */
    private function createRequest(): Request
    {
        return $request = new Request("PATCH", '/people/42', new FileStream($this->exampleFile));
    }
}