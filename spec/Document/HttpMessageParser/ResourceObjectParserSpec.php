<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Document\HttpMessageParser;

use Slick\JSONAPI\Document\HttpMessageParser\ResourceObjectParser;
use PhpSpec\ObjectBehavior;
use Slick\JSONAPI\Object\Link\LinkObject;
use Slick\JSONAPI\Object\Links;
use Slick\JSONAPI\Object\Meta;
use Slick\JSONAPI\Object\Relationships;
use Slick\JSONAPI\Object\ResourceObject;

/**
 * ResourceObjectParserSpec specs
 *
 * @package spec\Slick\JSONAPI\Document\HttpMessageParser
 */
class ResourceObjectParserSpec extends ObjectBehavior
{

    private $contentData;

    function let()
    {
        $file = dirname(__DIR__) . '/example.json';
        $this->contentData = json_decode(file_get_contents($file))->data;
        $this->beConstructedWith($this->contentData);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ResourceObjectParser::class);
    }

    function it_can_parse_a_resource_object()
    {
        $resource = $this->parse();
        $resource->shouldBeAnInstanceOf(ResourceObject::class);
        $resource->type()->shouldBe($this->contentData->type);
        $resource->identifier()->shouldBe($this->contentData->id);
    }

    function it_can_parse_resource_attributes()
    {
        $resource = $this->parse();
        $resource->attributes()->shouldBe([
            "name" => "John Doe",
            "email" => "john.doe@example.com"
        ]);
    }

    function it_can_parse_links()
    {
        $resource = $this->parse();
        $links = $resource->links();
        $links->shouldBeAnInstanceOf(Links::class);
        $links->get('self')->href()->shouldBe('http://example.com/people/42');
    }

    function it_can_parse_meta_information()
    {
        $resource = $this->parse();
        $meta = $resource->meta();
        $meta->shouldBeAnInstanceOf(Meta::class);
        $meta->get("description")->shouldBe("This is a person");
    }

    function it_can_parse_relations()
    {
        $resource = $this->parse();
        $resource->relationships()->shouldBeAnInstanceOf(Relationships::class);
    }
}