<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Document\Decoder\Fixtures;

use Slick\JSONAPI\Object\AbstractResourceSchema;
use Slick\JSONAPI\Object\ResourceObject;
use Slick\JSONAPI\Object\ResourceSchema;

/**
 * CreatePersonCommandSchema
 *
 * @package spec\Slick\JSONAPI\Document\Decoder\Fixtures
 */
final class CreatePersonCommandSchema extends AbstractResourceSchema implements ResourceSchema
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

    /**
     * @inheritDoc
     * @param ResourceObject $resourceObject
     */
    public function from($resourceObject)
    {
        return new CreatePersonCommand(
            $resourceObject->attributes()['name'],
            $resourceObject->attributes()['email']
        );
    }


}