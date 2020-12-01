<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI;

/**
 * Validator
 *
 * @package Slick\JSONAPI
 */
interface Validator
{

    /**
     * Check if provided subject is valid for this validator
     *
     * @param $subject
     * @param mixed|null $context
     * @return bool
     */
    public function isValid($subject, $context = null): bool;
}
