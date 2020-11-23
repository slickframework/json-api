<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Exception;

use RuntimeException;
use Slick\JSONAPI\JsonApiException;

/**
 * UnsupportedFeature
 *
 * Used when trying to use or add a known but unsupported feature. For example if
 * you try to add an extension to a JSON:API Object that is created with version
 * 1.0 an UnsupportedFeature exception is thrown.
 */
final class UnsupportedFeature extends RuntimeException implements JsonApiException
{

}
