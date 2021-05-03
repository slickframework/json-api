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
     * @var JsonApi|null
     */
    private $jsonapi = null;

    /**
     * @var Meta|null
     */
    private $meta = null;

    /**
     * @var Links|null
     */
    private $links = null;

    /**
     * @var string|null
     */
    private $linkPrefix = null;


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
        $document = $object instanceof Document ? $this->addData($object) : $this->documentFor($object);
        return $this->converter->convert($document);
    }

    /**
     * @inheritDoc
     */
    public function documentFor($object): Document
    {
        $schema = $this->discoverService->discover($object);
        return $this->factory->createDocument($schema, $object);
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
        $this->jsonapi = $jsonApi;
        $this->factory->withJsonapi($jsonApi);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withMeta(Meta $meta): DocumentEncoder
    {
        $this->meta = $meta;
        $this->factory->withMeta($meta);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withLinks(Links $links): DocumentEncoder
    {
        $this->links = $links;
        $this->factory->withLinks($links);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withLinkPrefix(string $linkPrefix): DocumentEncoder
    {
        $this->linkPrefix = $linkPrefix;
        $this->factory->withLinkPrefix($linkPrefix);
        return $this;
    }

    private function addData(Document $object): Document
    {
        $keys = ['jsonapi', 'meta', 'links', 'linkPrefix'];
        foreach ($keys as $prop) {
            $value = $this->$prop;
            if (!$value) {
                continue;
            }
            $object = call_user_func_array([$object, 'with'.ucfirst($prop)], [$value]);
        }
        return $object;
    }
}
