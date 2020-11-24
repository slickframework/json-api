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
 * FailedValidation
 *
 * @package Slick\JSONAPI\Exception
 */
final class FailedValidation extends InvalidArgumentException implements JsonApiException
{

}