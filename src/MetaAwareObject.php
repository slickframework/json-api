<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI;

use Slick\JSONAPI\Object\Meta;

/**
 * MetaAwareObject
 *
 * @package Slick\JSONAPI
 */
interface MetaAwareObject
{

    /**
     * A meta object that contains non-standard meta-information.
     *
     * @return Meta|null
     */
    public function meta(): ?Meta;

    /**
     * Returns a new document with provided Meta object
     *
     * This method will ALWAYS return a new copy (clone) of the document
     * maintaining object immutability.
     *
     * @param Meta $meta
     * @return MetaAwareObject
     */
    public function withMeta(Meta $meta): MetaAwareObject;


}