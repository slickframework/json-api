<?php

/**
 * This file is part of json-api
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Slick\JSONAPI;

use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slick\JSONAPI\Document\DocumentDecoder;
use Slick\JSONAPI\Document\HttpMessageParser;
use Slick\JSONAPI\Exception\InvalidResourceDocument;

/**
 * JsonApiParserMiddleware
 *
 * @package Slick\JSONAPI
 */
final class JsonApiParserMiddleware implements MiddlewareInterface
{
    /**
     * Creates a JsonApiParserMiddleware
     *
     * @param DocumentDecoder $documentDecoder
     * @param HttpMessageParser $parser
     */
    public function __construct(
        private readonly DocumentDecoder $documentDecoder,
        private readonly HttpMessageParser $parser
    ) {
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$this->hasJsonApiContent($request) || $this->emptyBody($request)) {
            return $handler->handle($request);
        }

        $document = $this->parseDocument($request);
        $request = $request->withParsedBody($document);
        $this->documentDecoder->setRequestedDocument($document);

        return $handler->handle($request);
    }

    /**
     * Parses the request body as a JSON:API document
     *
     * @param ServerRequestInterface $request The server request containing the document
     * @return Document The parsed JSON:API document
     *
     * @throws InvalidResourceDocument|JsonException If the request payload doesn't have a correctly
     *         formatted JSON:API document message.
     */
    public function parseDocument(ServerRequestInterface $request): Document
    {
        $request->getBody()->rewind();
        $data = json_decode($request->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

        if ($data === null) {
            throw new InvalidResourceDocument("Request payload doesn't have a formatted JSON:API document message.");
        }

        return $this->parser->parse($request);
    }

    /**
     * hasJsonApiContent
     *
     * @param ServerRequestInterface $request
     * @return bool
     */
    private function hasJsonApiContent(ServerRequestInterface $request): bool
    {
        $contentType = $request->hasHeader('content-type')
            ? $request->getHeaderLine('content-type')
            : 'text/plain';

        return str_contains($contentType, "application/vnd.api+json");
    }

    /**
     * Checks if the request body is empty or if the request method is TRACE
     *
     * @param ServerRequestInterface $request The server request to check
     * @return bool True if the request body is empty or if the request method is TRACE, false otherwise
     */
    private function emptyBody(ServerRequestInterface $request): bool
    {
        return strtoupper($request->getMethod()) === 'TRACE' || $request->getBody()->getSize() <= 0;
    }
}
