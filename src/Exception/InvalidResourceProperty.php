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
use Throwable;

/**
 * InvalidResourceProperty
 *
 * @package Slick\JSONAPI\Exception
 */
final class InvalidResourceProperty extends InvalidArgumentException implements JsonApiException
{

    /**
     * @var string
     */
    private $details;

    /**
     * @var string
     */
    private $pointer;

    /**
     * @var string
     */
    private $parameter;

    /**
     * Creates a InvalidResourceProperty
     *
     * @param string $title
     * @param string|null $code
     * @param Throwable|null $previous
     */
    private function __construct(string $title, ?string $code = "0", ?Throwable $previous = null)
    {
        parent::__construct($title, $code, $previous);
    }

    /**
     * Creates InvalidResourceProperty providing error data
     *
     * @param string $title
     * @param string|null $details
     * @param string|null $pointer
     * @param string|null $parameter
     * @param string|null $code
     * @param Throwable|null $previous
     *
     * @return InvalidResourceProperty
     */
    public static function error(
        string $title,
        ?string $details = null,
        ?string $pointer = null,
        ?string $parameter = null,
        ?string $code = "0",
        ?Throwable $previous = null
    ): InvalidResourceProperty {
        $ex = new InvalidResourceProperty($title, $code, $previous);
        $ex->details = $details;
        $ex->pointer = $pointer;
        $ex->parameter = $parameter;
        return $ex;
    }

    /**
     * code
     *
     * @return mixed
     */
    public function code()
    {
        return $this->code;
    }

    /**
     * details
     *
     * @return string
     */
    public function details(): string
    {
        return $this->details;
    }

    /**
     * pointer
     *
     * @return string
     */
    public function pointer(): string
    {
        return $this->pointer;
    }

    /**
     * parameter
     *
     * @return string
     */
    public function parameter(): string
    {
        return $this->parameter;
    }

    /**
     * title
     *
     * @return string
     */
    public function title(): string
    {
        return $this->message;
    }
}
