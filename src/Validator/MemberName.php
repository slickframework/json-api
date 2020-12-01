<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Validator;

use Slick\JSONAPI\Validator;

/**
 * MemberName
 *
 * @package Slick\JSONAPI\Validator
 */
final class MemberName implements Validator
{
    private static $parts = [
        '([\x{0000}-\x{001F}]+)',
        '([\x{0020}-\x{002C}]+)',
        '([\x{003A}-\x{003F}]+)',
        '([\x{007B}-\x{007F}]+)',
        //"([\x{0080}-\x{FFFF}]+)",
        '([\x{002E}\x{002F}\x{0040}\x{0060}]+)|([\x{005b}-\x{005E}]+)'
    ];

    /**
     * @inheritDoc
     */
    public function isValid($subject, $context = null): bool
    {
        $regexp = sprintf('/%s/iu', implode('|', self::$parts));
        return !(bool) preg_match_all($regexp, $subject);
    }
}
