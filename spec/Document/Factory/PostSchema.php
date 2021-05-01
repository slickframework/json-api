<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Document\Factory;

use Slick\JSONAPI\Document;
use Slick\JSONAPI\Object\AbstractResourceSchema;
use Slick\JSONAPI\Object\ResourceSchema;

/**
 * PostSchema
 *
 * @package Document\Factory
 */
final class PostSchema extends AbstractResourceSchema implements ResourceSchema
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
        return 'posts';
    }

    /**
     * @inheritDoc
     * @param Post $object
     */
    public function identifier($object): ?string
    {
        return $object->id();
    }

    /**
     * @inheritDoc
     * @param Post $object
     */
    public function attributes($object): ?array
    {
        return [
            'title' => $object->title()
        ];
    }

    /**
     * @inheritDoc
     * @param Post $object
     */
    public function relationships($object): ?array
    {
        return [
            'author' => [
                'links' => [
                    self::LINK_RELATED => true
                ],
                'data' => $object->author()
            ],
            'comments' => [
                'links' => [self::LINK_RELATED => true],
                'data' => $object->comments()
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function links($object): ?array
    {
        return [
            'self' => true
        ];
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
    public function from($resourceObject)
    {
        return null;
    }
}