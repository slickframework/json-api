<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Object\Relationships;

use Slick\JSONAPI\Object\Links;
use Slick\JSONAPI\Object\Meta;

/**
 * RelationshipMethods
 *
 * @package Slick\JSONAPI\Object\Relationships
 */
trait RelationshipMethods
{

    /**
     * @var Links|null
     */
    protected $links;

    /**
     * @var Meta|null
     */
    protected $meta;

    /**
     * @inheritDoc
     */
    public function links(): ?Links
    {
        return $this->links;
    }

    /**
     * @inheritDoc
     */
    public function meta(): ?Meta
    {
        return $this->meta;
    }
}
