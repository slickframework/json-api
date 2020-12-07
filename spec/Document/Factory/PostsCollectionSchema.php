<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Document\Factory;

use Doctrine\Common\Collections\ArrayCollection;
use Slick\JSONAPI\Object\ResourceCollectionSchema;

/**
 * PostsCollectionSchema
 *
 * @package Document\Factory
 */
final class PostsCollectionSchema implements ResourceCollectionSchema
{

    /**
     * @var ArrayCollection
     */
    private $data;

    public function __construct()
    {
        $this->data = new ArrayCollection();
        $personA = new Person('John Doe');
        $personB = new Person('Jane Doe');
        $personC = new Person('Donald Trump');
        $post1 = new Post($personA, 'I like this!');
        $post2 = new Post($personB, 'Another day at work...');
        $comment1 = new Comment($personB, 'me too...');
        $comment2 = new Comment($personC, 'No! I hate it! :~');
        $post1->addComment($comment1)->addComment($comment2);
        $post2->addComment(new Comment($personC, "not anymore! I'm leaving..."));
        $this->data->add($post1);
        $this->data->add($post2);
    }

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
     */
    public function identifier($object): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function attributes($object): ?array
    {
        return $this->data->toArray();
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
            'self' => '/posts'
        ];
    }

    /**
     * @inheritDoc
     */
    public function meta($object): ?array
    {
        return null;
    }
}
