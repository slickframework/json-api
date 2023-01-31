<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Document\Factory;

use Slick\JSONAPI\Document;
use Slick\JSONAPI\Document\DocumentFactory;
use Slick\JSONAPI\JsonApi;
use Slick\JSONAPI\Object\Links;
use Slick\JSONAPI\Object\Meta;
use Slick\JSONAPI\Object\Relationships;
use Slick\JSONAPI\Object\Resource as JsonApiResource;
use Slick\JSONAPI\Object\ResourceCollection;
use Slick\JSONAPI\Object\ResourceCollectionSchema;
use Slick\JSONAPI\Object\ResourceIdentifier;
use Slick\JSONAPI\Object\ResourceObject;
use Slick\JSONAPI\Object\ResourceSchema;
use Slick\JSONAPI\Object\SchemaDiscover;
use Traversable;

/**
 * DefaultFactory
 *
 * @package Slick\JSONAPI\Document\Factory
 */
final class DefaultFactory implements DocumentFactory
{
    const RELATION_NONE = 'none';
    const RELATION_TO_ONE = 'toOne';
    const RELATION_TO_MANY = 'toMany';
    /**
     * @var JsonApi|null
     */
    private ?JsonApi $jsonapi = null;

    /**
     * @var Links|null
     */
    private ?Links $links = null;

    /**
     * @var string|null
     */
    private ?string $linkPrefix = null;

    /**
     * @var Meta|null
     */
    private ?Meta $meta = null;

    /**
     * @var SchemaDiscover|null
     */
    private ?SchemaDiscover $discover;

    /**
     * @var SparseFields|null
     */
    private ?SparseFields $sparseFields = null;

    /**
     * Creates a DefaultFactory
     *
     * @param SchemaDiscover|null $discover
     */
    public function __construct(?SchemaDiscover $discover = null)
    {
        $this->discover = $discover;
    }

    /**
     * @inheritDoc
     */
    public function createDocument(ResourceSchema $schema, $object): Document
    {
        $resourceObject = $this->createResourceObject($schema, $object);
        $document = $schema->isCompound()
            ? new Document\ResourceCompoundDocument($resourceObject)
            : new Document\ResourceDocument($resourceObject);

        if ($document instanceof Document\ResourceCompoundDocument) {
            $document->withIncludedTypes($this->sparseFields()->includedTypes());
        }

        if (!$this->meta) {
            $this->meta = is_array($schema->meta($object)) ? new Meta($schema->meta($object)) : null;
        }

        if (!$this->links) {
            $this->links = is_array($schema->links($object)) ? $this->createResourceLinks($schema, $object): null;
        }

        $document = $this->jsonapi ? $document->withJsonapi($this->jsonapi) : $document;
        $document = $this->links ? $document->withLinks($this->links) : $document;
        return $this->meta ? $document->withMeta($this->meta) : $document;
    }

    /**
     * @inheritDoc
     */
    public function withJsonapi(JsonApi $jsonApi): DocumentFactory
    {
        $this->jsonapi = $jsonApi;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withMeta(Meta $meta): DocumentFactory
    {
        $this->meta = $meta;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withLinks(Links $links): DocumentFactory
    {
        $this->links = Links::checkLinks($links, $this->linkPrefix);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withLinkPrefix(string $linkPrefix): DocumentFactory
    {
        $this->linkPrefix = $linkPrefix;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withSchemaDiscover(SchemaDiscover $discover): DocumentFactory
    {
        $this->discover = $discover;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withSparseFields(SparseFields $sparseFields): DocumentFactory
    {
        $this->sparseFields = $sparseFields;
        return $this;
    }

    /**
     * sparseFields
     *
     * @return SparseFields
     */
    public function sparseFields(): SparseFields
    {
        if (!$this->sparseFields) {
            $this->sparseFields = new SparseFields();
        }
        return $this->sparseFields;
    }

    // Creation methods

    /**
     * createResourceIdentifier
     *
     * @param ResourceSchema $schema
     * @param $object
     * @return ResourceIdentifier
     */
    private function createResourceIdentifier(ResourceSchema $schema, $object): ResourceIdentifier
    {
        return new ResourceIdentifier(
            $schema->type($object),
            $schema->identifier($object)
        );
    }

    /**
     * createResourceLinks
     *
     * @param ResourceSchema $schema
     * @param $object
     * @return Links|null
     */
    private function createResourceLinks(ResourceSchema $schema, $object): ?Links
    {
        if (!$schema->links($object)) {
            return null;
        }

        $links = new Links($this->linkPrefix);
        foreach ($schema->links($object) as $rel => $href) {
            if ($rel === ResourceSchema::LINK_SELF && $href === true) {
                $links->add($rel, "/{$schema->type($object)}/{$schema->identifier($object)}");
                continue;
            }

            $links->add($rel, $href);
        }
        return $links;
    }

    /**
     * Creates resource's relationships
     *
     * @param ResourceSchema $schema
     * @param $object
     * @return Relationships|null
     */
    private function createRelationships(ResourceSchema $schema, $object): ?Relationships
    {
        $relationshipsData = $schema->relationships($object);
        if ($relationshipsData === null) {
            return null;
        }

        $relationships = new Relationships();
        $primaryId = new ResourceIdentifier($schema->type($object), $schema->identifier($object));
        foreach ($relationshipsData as $name => $subject) {
            if ($this->sparseFields()->hasFields() &&
                !$this->sparseFields()->includeField($name, $schema->type($object))
            ) {
                continue;
            }

            $type = array_key_exists('relationType', $subject) ? $subject['relationType'] : self::RELATION_NONE;
            $type = in_array($type, [self::RELATION_TO_ONE, self::RELATION_TO_MANY]) ? $type : self::RELATION_NONE;
            $isTraversable = is_array($subject['data']) || $subject['data'] instanceof Traversable;

            if ($type != self::RELATION_TO_ONE && $isTraversable) {
                $relationships->add($name, $this->createToManyRelation($primaryId, $name, $subject));
                continue;
            }

            $relationships->add($name, $this->createToOneRelation($primaryId, $name, $subject));
        }

        return $relationships->isEmpty() ? null : $relationships;
    }

    /**
     * Creates a ToMany Relationship object
     *
     * @param ResourceIdentifier $identifier
     * @param string $name
     * @param $subject
     * @return Relationships\ToManyRelationship
     */
    private function createToManyRelation(
        ResourceIdentifier $identifier,
        string $name,
        $subject
    ): Relationships\ToManyRelationship {
        $resourceCollection = new ResourceCollection($name);
        foreach ($subject['data'] as $member) {
            $resourceCollection->add(
                $this->createResourceFromSource($member)
            );
        }
        return new Relationships\ToManyRelationship(
            $resourceCollection,
            $this->createRelatedLinks($identifier, $name, $subject),
            $this->createRelatedMeta($subject)
        );
    }

    /**
     * createToOneRelation
     *
     * @param ResourceIdentifier $identifier
     * @param string $name
     * @param $subject
     * @return Relationships\ToOneRelationship
     */
    private function createToOneRelation(
        ResourceIdentifier $identifier,
        string $name,
        $subject
    ): Relationships\ToOneRelationship {
        $resource = $this->createResourceFromSource($subject['data']);
        return new Relationships\ToOneRelationship(
            $resource,
            $this->createRelatedLinks($identifier, $name, $subject),
            $this->createRelatedMeta($subject)
        );
    }

    /**
     * Creates a Related Resource
     *
     * @param ResourceSchema $schema
     * @param $object
     * @return ResourceObject
     */
    private function createRelatedResource(ResourceSchema $schema, $object): ResourceObject
    {
        return new ResourceObject(
            $this->createResourceIdentifier($schema, $object),
            $this->sparseFields()->filterFields($schema->type($object), $schema->attributes($object))
        );
    }

    /**
     * Creates related links
     *
     * @param ResourceIdentifier $identifier
     * @param string $relName
     * @param array $subject
     * @return Links|null
     */
    private function createRelatedLinks(ResourceIdentifier $identifier, string $relName, array $subject): ?Links
    {
        if (!array_key_exists('links', $subject)) {
            return null;
        }

        $links = new Links($this->linkPrefix);
        foreach ($subject['links'] as $name => $href) {
            if ($name === ResourceSchema::LINK_SELF && $href === true) {
                $links->add($name, "/{$identifier->type()}/{$identifier->identifier()}/relationships/$relName");
                continue;
            }

            if ($name === ResourceSchema::LINK_RELATED && $href === true) {
                $links->add($name, "/{$identifier->type()}/{$identifier->identifier()}/$relName");
                continue;
            }

            $links->add($name, $href);
        }
        return $links;
    }

    /**
     * Creates relationship Meta object
     *
     * @param array $subject
     * @return Meta|null
     */
    private function createRelatedMeta(array $subject): ?Meta
    {
        if (!array_key_exists('meta', $subject)) {
            return null;
        }

        return new Meta($subject['meta']);
    }

    /**
     * createResourceFromSource
     *
     * @param $subject
     * @return ResourceObject
     */
    private function createResourceFromSource($subject): ResourceObject
    {
        $schema = $subject instanceof ResourceSchema
            ? $subject
            : $this->discover->discover($subject);
        return $this->createRelatedResource($schema, $subject);
    }

    /**
     * createResourceObject
     *
     * @param ResourceSchema $schema
     * @param $object
     * @return JsonApiResource
     */
    private function createResourceObject(ResourceSchema $schema, $object): JsonApiResource
    {
        if ($schema instanceof ResourceCollectionSchema) {
            $resource = new ResourceCollection($schema->type($object));
            foreach ($schema->attributes($object) as $data) {
                if (!$this->sparseFields()->includeResource($schema->type($object))) {
                    continue;
                }
                $scm = $this->discover->discover($data);
                $resource->add($this->createResourceObject($scm, $data));
            }
            return $resource;
        }

        return new ResourceObject(
            $this->createResourceIdentifier($schema, $object),
            $this->sparseFields()->filterFields($schema->type($object), $schema->attributes($object)),
            $this->createRelationships($schema, $object),
            $this->createResourceLinks($schema, $object),
            is_array($schema->meta($object)) ? new Meta($schema->meta($object)) : null
        );
    }
}
