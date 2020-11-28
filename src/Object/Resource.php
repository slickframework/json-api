<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Object;

use JsonSerializable;

/**
 * Resource
 *
 * @package Slick\JSONAPI\Object
 */
interface Resource extends JsonSerializable
{

    /**
     * Resource type
     *
     * @return string
     */
    public function type(): string;

    /**
     * Resource identifier
     *
     * @return string|null
     */
    public function identifier(): ?string;

    /**
     * The resource identifier object
     *
     * @return ResourceIdentifier
     */
    public function resourceIdentifier(): ResourceIdentifier;
}
