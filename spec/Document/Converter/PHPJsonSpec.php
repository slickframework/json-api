<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Document\Converter;

use Slick\Http\Message\Uri;
use Slick\JSONAPI\Document\Converter\PHPJson;
use PhpSpec\ObjectBehavior;
use Slick\JSONAPI\Document\DocumentConverter;
use Slick\JSONAPI\Document\ErrorDocument;
use Slick\JSONAPI\Document\MetaDocument;
use Slick\JSONAPI\Document\ResourceCompoundDocument;
use Slick\JSONAPI\Document\ResourceDocument;
use Slick\JSONAPI\JsonApi;
use Slick\JSONAPI\Object\ErrorObject;
use Slick\JSONAPI\Object\Links;
use Slick\JSONAPI\Object\Meta;
use Slick\JSONAPI\Object\Relationships;
use Slick\JSONAPI\Object\ResourceCollection;
use Slick\JSONAPI\Object\ResourceIdentifier;
use Slick\JSONAPI\Object\ResourceObject;

/**
 * PHPJsonSpec specs
 *
 * @package spec\Slick\JSONAPI\Document\Converted
 */
class PHPJsonSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(PHPJson::class);
    }

    function its_a_converter()
    {
        $this->shouldBeAnInstanceOf(DocumentConverter::class);
    }

    function it_can_convert_meta_documents()
    {
        $expected = file_get_contents(__DIR__.'/meta-document.json');
        $doc = new MetaDocument(
            $this->meta(),
            $this->jsonApi(),
            $this->documentLinks()
        );
        $this->convert($doc)->shouldBe($expected);
    }

    function it_can_convert_resource_object_documents()
    {
        $expected = file_get_contents(__DIR__.'/resource-document.json');
        $linkPrefix = 'http://example.com';
        $doc = (new ResourceDocument(
            $this->articleResourceObject($linkPrefix),
            $this->documentLinks(),
            $this->meta()
        ))->withJsonapi($this->jsonApi());
        $this->convert($doc)->shouldBe($expected);
    }

    function it_can_convert_resource_compound_documents()
    {
        $expected = file_get_contents(__DIR__.'/resource-compound-document.json');
        $linkPrefix = 'http://example.com';
        $doc = new ResourceCompoundDocument($this->articleResourceObject($linkPrefix));
        $this->convert($doc)->shouldBe($expected);
    }

    function it_can_convert_resource_collection_compound_documents()
    {
        $expected = file_get_contents(__DIR__.'/resource-collection-compound.json');
        $linkPrefix = 'http://example.com';
        $collection = new ResourceCollection('articles', [$this->articleResourceObject($linkPrefix)]);
        $doc = new ResourceCompoundDocument($collection);
        $this->convert($doc)->shouldBe($expected);
    }

    function it_can_convert_error_documents()
    {
        $expected = file_get_contents(__DIR__.'/error-document.json');
        $doc = new ErrorDocument(
            new ResourceCollection(
                'errors',
                [
                    new ErrorObject(
                        'Value is too short',
                        'First name must contain at least three characters.',
                        new ErrorObject\ErrorSource('/data/attributes/firstName'),
                        '400'
                    ),
                    new ErrorObject(
                        'Passwords must contain a letter, number, and punctuation character.',
                        'The password provided is missing a punctuation character.',
                        new ErrorObject\ErrorSource('/data/attributes/password'),
                        '400'
                    )
                ]
            ),
            new JsonApi()
        );
        $this->convert($doc)->shouldBe($expected);
    }

    /**
     * jsonApi
     *
     * @return JsonApi
     */
    private function jsonApi(): JsonApi
    {
        return new JsonApi(
            JsonApi::JSON_API_11,
            [new Uri('https://jsonapi.org/ext/atomic')],
            [
                new Uri('http://example.com/profiles/flexible-pagination'),
                new Uri('http://example.com/profiles/resource-versioning'),
            ]
        );
    }

    /**
     * meta
     *
     * @return Meta
     */
    private function meta(): Meta
    {
        return new Meta(
            [
                'copyright' => 'Copyright 2015 Example Corp.',
                'authors' => [
                    "Yehuda Katz",
                    "Steve Klabnik",
                    "Dan Gebhardt",
                    "Tyler Kellen"
                ]
            ]
        );
    }

    /**
     * documentLinks
     *
     * @return Links
     */
    private function documentLinks(): Links
    {
        return (new Links())->add('about', '/about');
    }

    /**
     * articleResourceObject
     *
     * @param string $linkPrefix
     * @return ResourceObject
     */
    private function articleResourceObject(string $linkPrefix): ResourceObject
    {
        return new ResourceObject(
            new ResourceIdentifier('articles', '1'),
            ['title' => 'JSON:API paints my bikeshed!'],
            new Relationships([
                'author' => $this->articleAuthorRelationship($linkPrefix),
                'comments' => $this->articleCommentsRelationship($linkPrefix)
            ]),
            $this->articleLinks($linkPrefix)
        );
    }

    /**
     * articleAuthorRelationship
     *
     * @param string $linkPrefix
     * @return Relationships\ToOneRelationship
     */
    private function articleAuthorRelationship(string $linkPrefix): Relationships\ToOneRelationship
    {
        return new Relationships\ToOneRelationship(
            new ResourceObject(
                new ResourceIdentifier('people', "9"),
                ['name' => 'John Doe']
            ),
            (new Links($linkPrefix))
                ->add('self', '/articles/1/relationships/author')
                ->add('related', '/articles/1/author')
        );
    }

    /**
     * articleCommentsRelationship
     *
     * @param string $linkPrefix
     * @return Relationships\ToManyRelationship
     */
    private function articleCommentsRelationship(string $linkPrefix): Relationships\ToManyRelationship
    {
        return new Relationships\ToManyRelationship(
            new ResourceCollection('comments', [
                new ResourceObject(
                    new ResourceIdentifier('comments', "5"),
                    ['body' => 'First!'],
                    new Relationships([
                        'author' => new Relationships\ToOneRelationship(
                            new ResourceIdentifier('people', '2')
                        )
                    ]),
                    (new Links($linkPrefix))->add('self', '/comments/5')
                ),
                new ResourceObject(
                    new ResourceIdentifier('comments', "12"),
                    ['body' => 'I like XML better'],
                    new Relationships([
                        'author' => new Relationships\ToOneRelationship(
                            new ResourceIdentifier('people', '9')
                        )
                    ]),
                    (new Links($linkPrefix))->add('self', '/comments/12')
                )
            ]),
            (new Links($linkPrefix))
                ->add('self', '/articles/1/relationships/comments')
                ->add('related', '/articles/1/comments')
        );
    }

    /**
     * articleLinks
     *
     * @param string $linkPrefix
     * @return Links
     */
    private function articleLinks(string $linkPrefix): Links
    {
        return (new Links($linkPrefix))->add('self', '/articles/1');
    }
}