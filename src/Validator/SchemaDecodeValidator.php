<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Validator;

use Slick\JSONAPI\Exception\FailedValidation;
use Slick\JSONAPI\Object\ErrorObject;
use Slick\JSONAPI\Object\ErrorObject\ErrorSource;
use Slick\JSONAPI\Validator;

/**
 * SchemaDecodeValidator
 *
 * @package Slick\JSONAPI\Validator
 */
interface SchemaDecodeValidator extends Validator
{

    /**
     * Creates and adds an error to the validation stack
     *
     * @param string $title
     * @param string|null $detail
     * @param ErrorSource|null $source
     * @param string|null $status
     * @return $this
     */
    public function add(
        string $title,
        ?string $detail = null,
        ?ErrorSource $source = null,
        ?string $status = null
    ): self;

    /**
     * Returns the validation exception if any errors has been added.
     *
     * Implementations MUST create an Failed Exception when at least one
     * error has been added.
     *
     * @return FailedValidation|null
     */
    public function exception(): ?FailedValidation;
}