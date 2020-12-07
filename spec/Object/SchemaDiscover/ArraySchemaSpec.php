<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Object\SchemaDiscover;

use Slick\JSONAPI\Exception\DocumentEncoderFailure;
use Slick\JSONAPI\Object\ResourceSchema;
use Slick\JSONAPI\Object\SchemaDiscover\ArraySchema;
use PhpSpec\ObjectBehavior;

/**
 * ArraySchemaSpec specs
 *
 * @package spec\Slick\JSONAPI\Object\SchemaDiscover
 */
class ArraySchemaSpec extends ObjectBehavior
{

    private $data;

    function let()
    {
        $md5 = md5(time());
        $this->data = [
            'type' => 'tests',
            'identifier' => $md5,
            'attributes' => [
                'foo' => 'bar'
            ],
            'links' => [
                ResourceSchema::LINK_SELF => true
            ],
            'relationships' => [
                'result' => (object)[]
            ],
            'meta' => [
                'copyright' => 'Someone at 2020'
            ]
        ];
        $this->beConstructedWith($this->data);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ArraySchema::class);
    }

    function its_a_resource_schema()
    {
        $this->shouldBeAnInstanceOf(ResourceSchema::class);
    }

    function it_has_a_type()
    {
        $this->type('test')->shouldBe($this->data['type']);
    }

    function it_has_a_identifier()
    {
        $this->identifier('test')->shouldBe($this->data['identifier']);
    }

    function it_has_a_attributes()
    {
        $this->attributes('test')->shouldBe($this->data['attributes']);
    }

    function it_has_a_links()
    {
        $this->links('test')->shouldBe($this->data['links']);
    }

    function it_has_a_meta()
    {
        $this->meta('test')->shouldBe($this->data['meta']);
    }

    function it_has_a_relationships()
    {
        $this->relationships('test')->shouldBe($this->data['relationships']);
    }

    function it_throws_exception_when_type_is_not_defined()
    {
        $this->beConstructedWith([]);
        $this->shouldThrow(DocumentEncoderFailure::class)
            ->duringInstantiation();
    }
}