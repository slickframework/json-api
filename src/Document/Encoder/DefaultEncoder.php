<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Document\Encoder;

use Slick\JSONAPI\Document;
use Slick\JSONAPI\Document\DocumentConverter;
use Slick\JSONAPI\Document\DocumentEncoder;
use Slick\JSONAPI\Document\DocumentFactory;
use Slick\JSONAPI\JsonApi;
use Slick\JSONAPI\Object\Links;
use Slick\JSONAPI\Object\Meta;
use Slick\JSONAPI\Object\SchemaDiscover;

/**
 * DefaultEncoder
 *
 * @package Slick\JSONAPI\Document\Encoder
 */
final class DefaultEncoder implements DocumentEncoder
{
    /**
     * @var SchemaDiscover
     */
    private $discoverService;
    /**
     * @var DocumentFactory
     */
    private $factory;
    /**
     * @var DocumentConverter
     */
    private $converter;


    /**
     * Creates a DefaultEncoder
     *
     * @param SchemaDiscover $discoverService
     * @param DocumentFactory $factory
     * @param DocumentConverter $converter
     */
    public function __construct(SchemaDiscover $discoverService, DocumentFactory $factory, DocumentConverter $converter)
    {
        $this->discoverService = $discoverService;
        $this->factory = $factory;
        $this->converter = $converter;
    }

    /**
     * @inheritDoc
     */
    public function encode($object): string
    {
        $document = $object instanceof Document ? $object : $this->documentFor($object);
        return $this->converter->convert($document);
    }

    /**
     * @inheritDoc
     */
    public function documentFor($object): Document
    {
        $schema = $this->discoverService->discover($object);
        $document = $this->factory->createDocument($schema, $object);
        return $document;
    }

    /**
     * @inheritDoc
     */
    public function schemaDiscover(): SchemaDiscover
    {
        return $this->discoverService;
    }

    /**
     * @inheritDoc
     */
    public function withJsonapi(JsonApi $jsonApi): DocumentEncoder
    {
        $this->factory->withJsonapi($jsonApi);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withMeta(Meta $meta): DocumentEncoder
    {
        $this->factory->withMeta($meta);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withLinks(Links $links): DocumentEncoder
    {
        $this->factory->withLinks($links);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withLinkPrefix(string $linkPrefix): DocumentEncoder
    {
        $this->factory->withLinkPrefix($linkPrefix);
        return $this;
    }
}
