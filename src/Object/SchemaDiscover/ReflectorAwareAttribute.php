<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);


namespace Slick\JSONAPI\Object\SchemaDiscover;

use Reflector;

/**
 * PropertyAwareAttribute
 *
 * @package Slick\JSONAPI\Object\SchemaDiscover
 */
interface ReflectorAwareAttribute
{

    /**
     * Sets the reflection property to work with
     *
     * @param Reflector $property
     * @return self
     */
    public function withProperty(Reflector $property): self;
}
