<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Exception;

use InvalidArgumentException;
use Slick\JSONAPI\Document\ErrorDocument;
use Slick\JSONAPI\JsonApiException;
use Slick\JSONAPI\Object\ErrorObject;
use Slick\JSONAPI\Object\ResourceCollection;
use Throwable;

/**
 * FailedValidation
 *
 * @package Slick\JSONAPI\Exception
 */
final class FailedValidation extends InvalidArgumentException implements JsonApiException
{

    /**
     * @var ErrorDocument
     */
    private $document;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->document = new ErrorDocument(new ResourceCollection('errors'));
    }

    public function addError(ErrorObject $error): self
    {
        $this->document->errors()->add($error);
        return $this;
    }

    /**
     * document
     *
     * @return ErrorDocument
     */
    public function document(): ErrorDocument
    {
        return $this->document;
    }
}
