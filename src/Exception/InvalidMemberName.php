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
 * InvalidMemberName
 *
 * @package Slick\JSONAPI\Exception
 */
final class InvalidMemberName extends InvalidArgumentException implements JsonApiException
{

}
