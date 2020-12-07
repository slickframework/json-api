<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Object\SchemaDiscover;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Slick\JSONAPI\Exception\DocumentEncoderFailure;
use Slick\JSONAPI\Object\ResourceSchema;

/**
 * ArraySchema
 *
 * @package Slick\JSONAPI\Object\SchemaDiscover
 */
final class ArraySchema implements ResourceSchema
{
    /**
     * @var Collection
     */
    private $data;

    /**
     * Creates a ArraySchema
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        if (!array_key_exists('type', $data)) {
            throw new DocumentEncoderFailure(
                "'type' is a mandatory resource member on JSON:API. Provided array doesn't provide it."
            );
        }
        $this->data = new ArrayCollection($data);
    }

    /**
     * @inheritDoc
     */
    public function type($object): string
    {
        return $this->data->get('type');
    }

    /**
     * @inheritDoc
     */
    public function identifier($object): ?string
    {
        return $this->data->get('identifier');
    }

    /**
     * @inheritDoc
     */
    public function attributes($object): ?array
    {
        return $this->data->get('attributes');
    }

    /**
     * @inheritDoc
     */
    public function relationships($object): ?array
    {
        return $this->data->get('relationships');
    }

    /**
     * @inheritDoc
     */
    public function links($object): ?array
    {
        return $this->data->get('links');
    }

    /**
     * @inheritDoc
     */
    public function meta($object): ?array
    {
        return $this->data->get('meta');
    }

    /**
     * @inheritDoc
     */
    public function isCompound(): bool
    {
        return (bool) $this->data->get('compound');
    }
}
