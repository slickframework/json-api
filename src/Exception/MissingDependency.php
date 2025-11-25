<?php

/**
 * This file is part of json-api
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Slick\JSONAPI\Exception;

use Slick\JSONAPI\JsonApiException;

/**
 * MissingDependency
 *
 * @package Slick\JSONAPI\Exception
 */
final class MissingDependency extends \RuntimeException implements JsonApiException
{

}
