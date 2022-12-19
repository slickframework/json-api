<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Slick\JSONAPI\Object\SchemaDiscover\Attributes;

use Attribute;

/**
 * AsResourceCollection
 *
 * @package Slick\JSONAPI\Object\SchemaDiscover\Attributes
 */
#[Attribute(Attribute::TARGET_CLASS)]
class AsResourceCollection extends AsResourceObject
{

}
