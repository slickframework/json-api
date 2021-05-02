<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Validator;

use Slick\JSONAPI\Exception\FailedValidation;
use Slick\JSONAPI\Object\ErrorObject;
use Slick\JSONAPI\Object\ErrorObject\ErrorSource;

/**
 * SchemaValidator
 *
 * @package Slick\JSONAPI\Validator
 */
final class SchemaValidator implements SchemaDecodeValidator
{

    /**
     * @var FailedValidation|null
     */
    private $exception = null;

    /**
     * @inheritDoc
     */
    public function isValid($subject, $context = null): bool
    {
        return !$this->exception;
    }

    /**
     * @inheritDoc
     */
    public function exception(): ?FailedValidation
    {
        return $this->exception;
    }

    public function add(
        string $title,
        ?string $detail = null,
        ?ErrorSource $source = null,
        ?string $status = null
    ): SchemaDecodeValidator {
        $this->exception = $this->exception ?: new FailedValidation("Fail to validate JSON:API document.");
        $error = new ErrorObject($title, $detail, $source, $status);
        $this->exception()->addError($error);
        return $this;
    }
}
