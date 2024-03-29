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
use Slick\JSONAPI\Document\Factory\SparseFields;
use Slick\JSONAPI\JsonApi;
use Slick\JSONAPI\LinksAwareObject;
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
    private ?JsonApi $jsonapi = null;
    private ?Meta $meta = null;
    private ?Meta $documentMeta = null;
    private ?Links $links = null;
    private ?Links $documentLinks = null;
    private ?string $linkPrefix = null;

    /**
     * Creates a DefaultEncoder
     *
     * @param SchemaDiscover $discoverService
     * @param DocumentFactory $factory
     * @param DocumentConverter $converter
     */
    public function __construct(
        private SchemaDiscover $discoverService,
        private DocumentFactory $factory,
        private DocumentConverter $converter
    ) {
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
        $this->factory->withLinks($links);
        $this->links = Links::checkLinks($links, $this->linkPrefix);
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

    /**
     * @inheritDoc
     */
    public function withSparseFields(SparseFields $sparseFields): DocumentEncoder
    {
        $this->factory->withSparseFields($sparseFields);
        return $this;
    }

    private function addData(Document $object): Document
    {
        $object = $this->addJsonApi($object);
        $object = $this->addMetaData($object);
        $object = $this->addLinks($object);

        return $this->checkResourceLinks($object);
    }

    /**
     * Adds JSON API version to provided document if it exists
     *
     * @param Document $document
     * @return Document
     */
    private function addJsonApi(Document $document): Document
    {
        if (!$this->jsonapi) {
            return $document;
        }

        return $document->withJsonapi($this->jsonapi);
    }

    /**
     * Adds Metadata to provided document if it exists
     *
     * @param Document $document
     * @return Document
     */
    private function addMetaData(Document $document): Document
    {
        if (!$this->meta) {
            return $document;
        }

        return $document->withMeta($this->meta);
    }

    /**
     * Adds links with prefix if present
     *
     * @param Document $document
     * @return Document
     */
    private function addLinks(Document $document): Document
    {
        if (!$this->links) {
            return $document;
        }

        foreach ($document->links() as $rel => $link) {
            $this->links->add($rel, $link->href());
        }

        return $document->withLinks($this->links);
    }

    /**
     * checkResourceLinks
     *
     * @param Document $document
     * @return Document
     */
    private function checkResourceLinks(Document $document): Document
    {
        if ($this->linkPrefix) {
            return $document;
        }

        if (!($document->data() instanceof LinksAwareObject)) {
            return $document;
        }

        $links = $document->data()->links();
        if (!$links) {
            return $document;
        }

        $newLinks = new Links($this->linkPrefix);
        foreach ($links as $rel => $ln) {
            $newLinks->add($rel, $ln->href());
        }

        $data = $document->data()->withLinks($newLinks);
        return $document->withData($data);
    }

    public function withDocumentLinks(Links $links): DocumentEncoder
    {
        $this->documentLinks = $links;
        $this->factory->withDocumentLinks($links);
        return $this;
    }

    public function withDocumentMeta(Meta $meta): DocumentEncoder
    {
        $this->documentMeta = $meta;
        $this->factory->withDocumentMeta($meta);
        return $this;
    }
}
