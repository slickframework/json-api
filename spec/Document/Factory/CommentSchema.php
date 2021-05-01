<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Document\Factory;

use Slick\JSONAPI\Document;
use Slick\JSONAPI\Object\ResourceSchema;

/**
 * CommentSchema
 *
 * @package Document\Factory
 */
final class CommentSchema implements ResourceSchema
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
        return 'comments';
    }

    /**
     * @inheritDoc
     * @param Comment $object
     */
    public function identifier($object): ?string
    {
        return $object->id();
    }

    /**
     * @inheritDoc
     * @param Comment $object
     */
    public function attributes($object): ?array
    {
        return [
            'body' => $object->body()
        ];
    }

    /**
     * @inheritDoc
     * @param Comment $object
     */
    public function relationships($object): ?array
    {
        return [
            'author' => [
                'links' => [
                    self::LINK_SELF => true,
                    self::LINK_RELATED => true
                ],
                'data' => $object->person()
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

    /**
     * @inheritDoc
     */
    public function validate(Document $document): void
    {
        // TODO: Implement validate() method.
    }
}