<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Exception;

use InvalidArgumentException;
use Slick\JSONAPI\JsonApiException;

/**
 * UnknownValidator
 *
 * @package Slick\JSONAPI\Exception
 */
final class UnknownValidator extends InvalidArgumentException implements JsonApiException
{

}
