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
 * DocumentEncoderFailure
 *
 * @package Slick\JSONAPI\Exception
 */
final class DocumentEncoderFailure extends RuntimeException implements JsonApiException
{

}
