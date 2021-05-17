<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Document;

use Slick\JSONAPI\Object\Relationships\ToOneRelationship;
use Slick\JSONAPI\Object\Resource;
use Slick\JSONAPI\Object\ResourceCollection;
use Slick\JSONAPI\Object\ResourceObject;

/**
 * ResourceCompoundDocument
 *
 * @package Slick\JSONAPI\Document
 */
final class ResourceCompoundDocument extends ResourceDocument
{

    private $includedTypes = null;

    /**
     * Sets the included types
     *
     * @param array|null $types
     * @return ResourceCompoundDocument
     */
    public function withIncludedTypes(?array $types = null): self
    {
        $this->includedTypes = $types;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function included(): ?array
    {
        if ($this->data instanceof ResourceCollection) {
            return $this->extractFromCollection();
        }

        if (!$this->data->relationships()) {
            return null;
        }

        return $this->extractIncludedResources();
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $data = parent::jsonSerialize();
        $included = $this->included();
        if ($included) {
            $data['included'] = $included;
        }
        return $data;
    }

    /**
     * Retrieve resource and includes it in provided data array
     *
     * @param array $data
     * @param Resource|ResourceObject $resource
     */
    private function retrieveResource(array &$data, Resource $resource)
    {
        $key = $resource->type().$resource->identifier();
        if ($this->includedTypes && !in_array($resource->type(), $this->includedTypes)) {
            return;
        }
        if (method_exists($resource, 'attributes') && !$resource->attributes()) {
            return;
        }
        $data[$key] = $resource;
    }

    private function extractFromCollection(): array
    {
        $data = [];
        /** @var ResourceObject $resource */
        foreach ($this->data as $resource) {
            foreach ($resource->relationships() as $relationship) {
                if ($relationship instanceof ToOneRelationship) {
                    $this->retrieveResource($data, $relationship->data());
                    continue;
                }

                foreach ($relationship->data() as $rel) {
                    $this->retrieveResource($data, $rel);
                }
            }
        }

        return array_values($data);
    }

    /**
     * extractIncludedResources
     *
     * @return array
     */
    private function extractIncludedResources(): array
    {
        $data = [];
        foreach ($this->data->relationships() as $relationship) {
            if ($relationship instanceof ToOneRelationship) {
                $this->retrieveResource($data, $relationship->data());
                continue;
            }

            foreach ($relationship->data() as $rel) {
                $this->retrieveResource($data, $rel);
            }
        }

        return array_values($data);
    }
}
