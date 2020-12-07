<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Document\Factory;

use Slick\JSONAPI\Object\ResourceSchema;

/**
 * PersonSchema
 *
 * @package Document\Factory
 */
final class PersonSchema implements ResourceSchema
{

    /**
     * @inheritDoc
     */
    public function isCompound(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function type($object): string
    {
        return 'people';
    }

    /**
     * @inheritDoc
     * @param Person $object
     */
    public function identifier($object): ?string
    {
        return $object->id();
    }

    /**
     * @inheritDoc
     * @param Person $object
     */
    public function attributes($object): ?array
    {
        return [
            'name' => $object->name()
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
        return [
           self::LINK_SELF => true
        ];
    }

    /**
     * @inheritDoc
     */
    public function meta($object): ?array
    {
        return [
            'copyright' => 'Test corp. 2020'
        ];
    }
}