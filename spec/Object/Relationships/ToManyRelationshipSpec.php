<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Object\Relationships;

use Slick\JSONAPI\Object\Links;
use Slick\JSONAPI\Object\Meta;
use Slick\JSONAPI\Object\Relationship;
use Slick\JSONAPI\Object\Relationships\ToManyRelationship;
use PhpSpec\ObjectBehavior;
use Slick\JSONAPI\Object\Resource;
use Slick\JSONAPI\Object\ResourceCollection;
use Slick\JSONAPI\Object\ResourceIdentifier;

/**
 * ToManyRelationshipSpec specs
 *
 * @package spec\Slick\JSONAPI\Object\Relationships
 */
class ToManyRelationshipSpec extends ObjectBehavior
{
    /** @var Resource */
    private $resource;
    private $links;
    private $meta;
    /**
     * @var Resource[]
     */
    private $data;

    function let()
    {
        $this->links = new Links();
        $this->links->add('self', '/articles/2');
        $this->resource = new ResourceIdentifier('people', '1');
        $resource = new ResourceIdentifier('people', '1');
        $this->meta = new Meta(['foo' => 'bar']);
        $this->data = new ResourceCollection([$this->resource, $resource]);
        $this->beConstructedWith($this->data, $this->links, $this->meta);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ToManyRelationship::class);
    }

    function its_a_relationship()
    {
        $this->shouldBeAnInstanceOf(Relationship::class);
    }

    function it_has_a_data()
    {
        $this->data()->shouldBe($this->data);
    }

    function it_has_a_links()
    {
        $this->links()->shouldBe($this->links);
    }

    function it_has_a_meta()
    {
        $this->meta()->shouldBe($this->meta);
    }

    function it_can_be_converted_to_json()
    {
        $data = [];
        foreach ($this->data as $datum) {
            $data[] = $datum->resourceIdentifier();
        }
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            'links' => $this->links,
            'data' => $data,
            'meta' => $this->meta
        ]);
    }
}