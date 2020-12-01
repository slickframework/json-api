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
 * Relationship
 *
 * @package Slick\JSONAPI\Object
 */
interface Relationship extends JsonSerializable
{

    /**
     * Relationship links
     *
     * @return Links
     */
    public function links(): ?Links;

    /**
     * Resource linkage
     *
     * @return ResourceIdentifier|array<ResourceIdentifier>|null
     */
    public function data();

    /**
     * A meta object that contains non-standard meta-information about the relationship.
     *
     * @return Meta
     */
    public function meta(): ?Meta;
}
