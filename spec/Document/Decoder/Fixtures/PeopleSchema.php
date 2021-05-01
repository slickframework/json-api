<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Document\Decoder\Fixtures;

use Slick\JSONAPI\Object\AbstractResourceSchema;

/**
 * PeopleSchema
 *
 * @package spec\Slick\JSONAPI\Document\Decoder\Fixtures
 */
final class PeopleSchema extends AbstractResourceSchema
{

    /**
     * @inheritDoc
     */
    public function type($object): string
    {
        return 'people';
    }

    /**
     * @inheritDoc
     */
    public function identifier($object): ?string
    {
        return md5(microtime());
    }
}