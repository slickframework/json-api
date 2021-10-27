<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Document\Factory;

use Slick\JSONAPI\Document\Converter\PHPJson;
use Slick\JSONAPI\Document\DocumentFactory;
use Slick\JSONAPI\Document\Factory\DefaultFactory;
use PhpSpec\ObjectBehavior;
use Slick\JSONAPI\Document\ResourceCompoundDocument;
use Slick\JSONAPI\Document\ResourceDocument;
use Slick\JSONAPI\JsonApi;
use Slick\JSONAPI\Object\Links;
use Slick\JSONAPI\Object\Meta;
use Slick\JSONAPI\Object\Relationships\ToManyRelationship;
use Slick\JSONAPI\Object\Relationships\ToOneRelationship;
use Slick\JSONAPI\Object\ResourceObject;
use Slick\JSONAPI\Object\ResourceSchema;
use Slick\JSONAPI\Object\SchemaDiscover;
use Slick\JSONAPI\Object\SchemaDiscover\ArraySchema;

/**
 * DefaultFactorySpec specs
 *
 * @package spec\Slick\JSONAPI\Document\Factory
 */
class DefaultFactorySpec extends ObjectBehavior
{

    function let(SchemaDiscover $discover)
    {
        $discover->discover('author')->willReturn($this->createRelated());
        $this->beConstructedWith($discover);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DefaultFactory::class);
    }

    function its_a_document_factory()
    {
        $this->shouldBeAnInstanceOf(DocumentFactory::class);
    }

    function it_can_create_a_document()
    {
        $schema = $this->createArraySchema();
        $document = $this->createDocument($schema, 'test');
        $document->shouldBeAnInstanceOf(ResourceDocument::class);
    }

    function it_can_add_json_api_member_to_document()
    {
        $jsonApi = new JsonApi(JsonApi::JSON_API_11, ['http://api.com/ext/test']);
        $this->withJsonapi($jsonApi);
        $document = $this->createDocument($this->createArraySchema(), 'test');
        $document->jsonapi()->shouldBe($jsonApi);
    }

    function it_can_add_links_member_to_document()
    {
        $links = new Links();
        $href = '/tests/about';
        $links->add('about', $href);
        $this->withLinks($links)->shouldBe($this->getWrappedObject());
        $document = $this->createDocument($this->createArraySchema(), 'test');
        $document->links()->get('about')->href()->shouldBe($href);
    }

    function it_can_add_link_prefix_to_the_document_links()
    {
        $links = new Links();
        $href = '/tests/about';
        $prefix = 'https://example.com';
        $links->add('about', $href);
        $this
            ->withLinkPrefix($prefix)
            ->withLinks($links);
        $document = $this->createDocument($this->createArraySchema(), 'test');
        $document->links()->get('about')->href()->shouldBe($prefix.$href);
    }

    function it_can_construct_resource_links()
    {
        $prefix = 'https://example.com';
        $schema = $this->createArraySchema();
        $this->withLinkPrefix($prefix);

        $document = $this->createDocument($schema, 'test');
        $link = $document->data()->links()->get('self');
        $link->href()->shouldBe($prefix.'/tests/3');
    }

    function it_can_create_a_document_with_mete_info()
    {
        $meta = new Meta(['foo' => 'bar']);
        $this->withMeta($meta)->shouldBe($this->getWrappedObject());
        $document = $this->createDocument($this->createArraySchema(), 'test');
        $document->meta()->shouldBe($meta);
    }

    function it_can_create_resource_meta()
    {
        $document = $this->createDocument($this->createArraySchema(), 'test');
        $document->data()->meta()->get('foo')->shouldBe('bar');
    }

    function it_can_create_relationships()
    {
        $document = $this->createDocument($this->createArraySchema(), 'test');
        $document->data()->shouldBeAnInstanceOf(ResourceObject::class);
        $relationship = $document->data()->relationships()->get('author');
        $relationship->shouldBeAnInstanceOf(ToOneRelationship::class);
        $relationship->links()->get('self')->href()->shouldBe('/tests/3/relationships/author');
        $relationship->links()->get('related')->href()->shouldBe('/tests/3/author');

        $toMany = $document->data()->relationships()->get('members');
        $toMany->shouldBeAnInstanceOf(ToManyRelationship::class);
        $toMany->links()->get('self')->href()->shouldBe('/tests/3/relationships/members');
        $toMany->links()->get('related')->href()->shouldBe('/tests/3/members');
    }

    function it_can_create_resource_collection_document()
    {
        $discover = new SchemaDiscover\ClassMapDiscover([
            Post::class => PostSchema::class,
            Comment::class => CommentSchema::class,
            Person::class => PersonSchema::class,
            'posts' => PostsCollectionSchema::class
        ]);
        $this
            ->withSchemaDiscover($discover)
            ->withLinkPrefix('https://www.example.com/');
        $document = $this->createDocument($discover->discover('posts'), false);
        $document->shouldBeAnInstanceOf(ResourceCompoundDocument::class);
        $document->meta()->get('foo')->shouldBe('bar');
        $converter = new PHPJson();
        //echo $converter->convert($document->getWrappedObject());
    }

    /**
     * createArraySchema
     *
     * @return ArraySchema
     */
    private function createArraySchema(): ArraySchema
    {
        return new ArraySchema([
            'type' => 'tests',
            'identifier' => '3',
            'attributes' => [
                'foo' => 'bar'
            ],
            'relationships' => [
                'author' => [
                    'links' => [
                        ResourceSchema::LINK_SELF => true,
                        ResourceSchema::LINK_RELATED => true,
                    ],
                    'data' => 'author'
                ],
                'members' => [
                    'links' => [
                        ResourceSchema::LINK_SELF => true,
                        ResourceSchema::LINK_RELATED => true,
                    ],
                    'data' => ['author', 'author']
                ]
            ],
            'links' => [
                ResourceSchema::LINK_SELF => true
            ],
            'meta' => [
                'foo' => 'bar'
            ]
        ]);
    }

    private function createRelated(): ArraySchema
    {
        return new ArraySchema([
            'type' => 'user',
            'identifier' => '30',
            'attributes' => [
                'name' => 'John Doe'
            ],
            'links' => [
                ResourceSchema::LINK_SELF => true
            ]
        ]);
    }
}