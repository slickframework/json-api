<?php

/**
 * This file is part of json-api
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Slick\JSONAPI;

use Slick\ErrorHandler\Exception\ExceptionInspector;
use Slick\ErrorHandler\Handler\HandlerInterface;
use Slick\ErrorHandler\RunnerInterface;
use Slick\JSONAPI\Object\ErrorObject;
use Throwable;

/**
 * JsonApiErrorHandler
 *
 * @package Slick\JSONAPI
 */
final class JsonApiErrorHandler implements HandlerInterface
{

    /**
     * Handle a Throwable and generate a JSON API error response
     *
     * @param Throwable $throwable The throwable object
     * @param ExceptionInspector $inspector The inspector to get exception details
     * @param RunnerInterface $runner The runner responsible for handling the request
     * @return int|null The exit status code indicating further action
     */
    public function handle(Throwable $throwable, ExceptionInspector $inspector, RunnerInterface $runner): ?int
    {
        $runner->outputHeaders(['Content-Type' => 'application/vnd.api+json']);
        $runner->sendResponseCode($inspector->statusCode());
        $errorMessage = $throwable->getMessage() ? $throwable->getMessage(). " " : null;
        $errorDetail = $errorMessage . preg_split('/\r?\n/', ltrim((string)$inspector->help()), 2)[0];

        $jsonApiError = new ErrorObject(
            title: $this->clearTitle($throwable),
            detail: trim($errorDetail),
            status: (string) $inspector->statusCode()
        );
        $response = [
            "jsonapi" => ["version" => JsonApi::JSON_API_11],
            "errors" => [
                $jsonApiError->withIdentifier(uniqid())
            ]
        ];

        echo json_encode($response, JSON_PRETTY_PRINT);
        return $this::QUIT;
    }

    /**
     * Clear and format the title of a Throwable object
     *
     * @param Throwable $throwable The throwable object
     * @return string The formatted title
     */
    private function clearTitle(Throwable $throwable): string
    {
        $parts = explode('\\', get_class($throwable));
        $name = array_pop($parts);
        return ucfirst(trim(strtolower(implode(' ', preg_split('/(?=[A-Z])/', $name)))));
    }
}
