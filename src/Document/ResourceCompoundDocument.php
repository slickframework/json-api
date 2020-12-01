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
    /**
     * @inheritDoc
     */
    public function included(): ?array
    {
        $data = [];

        if ($this->data instanceof ResourceCollection) {
            return $this->extractFromCollection();
        }

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

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $data = parent::jsonSerialize();
        $data['included'] = $this->included();
        return $data;
    }

    /**
     * Retrieve resource and includes it in provided data array
     *
     * @param array $data
     * @param Resource $resource
     */
    private function retrieveResource(array &$data, Resource $resource)
    {
        $key = $resource->type().$resource->identifier();
        $data[$key] = $resource;
    }

    private function extractFromCollection()
    {
        $data = [];
        /** @var ResourceObject[] $resource */
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
}
