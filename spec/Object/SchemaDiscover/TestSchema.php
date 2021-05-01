<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Object\SchemaDiscover;

use Slick\JSONAPI\Document;
use Slick\JSONAPI\Object\AbstractResourceSchema;
use Slick\JSONAPI\Object\ResourceSchema;

/**
 * TestSchema
 *
 * @package Object\SchemaDiscover
 */
final class TestSchema extends AbstractResourceSchema implements ResourceSchema
{

    /**
     * @inheritDoc
     */
    public function type($object): string
    {
        return 'tests';
    }

    /**
     * @inheritDoc
     */
    public function identifier($object): ?string
    {
        return md5(time());
    }

    /**
     * @inheritDoc
     */
    public function attributes($object): ?array
    {
        return [
            'foo' => 'bar'
        ];
    }

    /**
     * @inheritDoc
     */
    public function relationships($object): ?array
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function links($object): ?array
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function meta($object): ?array
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function isCompound(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function from($resourceObject)
    {
        return null;
    }
}