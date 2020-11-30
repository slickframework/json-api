<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Document;

use Slick\JSONAPI\Document;
use Slick\JSONAPI\Document\ResourceDocument;
use PhpSpec\ObjectBehavior;
use Slick\JSONAPI\Object\Links;
use Slick\JSONAPI\Object\Meta;
use Slick\JSONAPI\Object\Relationships;
use Slick\JSONAPI\Object\Resource as ResourceObj;
use Slick\JSONAPI\Object\ResourceObject;

/**
 * ResourceDocumentSpec specs
 *
 * @package spec\Slick\JSONAPI\Document
 */
class ResourceDocumentSpec extends ObjectBehavior
{

    private $links;
    private $meta;

    function let(ResourceObject $resource, ResourceObj $related)
    {
        $relations = new Relationships([
            'related' => new Relationships\ToOneRelationship($related->getWrappedObject())
        ]);
        $resource->relationships()->willReturn($relations);

        $this->links = new Links();
        $this->meta = new Meta(['foo' => 'bar']);
        $this->beConstructedWith($resource, $this->links, $this->meta);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ResourceDocument::class);
    }

    function its_a_document()
    {
        $this->shouldBeAnInstanceOf(Document::class);
    }

    function it_can_be_converted_to_json(ResourceObject $resource)
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            'data' => $resource,
            'links' => $this->links,
            'meta' => $this->meta
        ]);
    }
}