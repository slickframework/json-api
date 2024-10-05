<?php

/**
 * This file is part of json-api
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Slick\JSONAPI;

use Slick\ModuleApi\Infrastructure\AbstractModule;
use Slick\ModuleApi\Infrastructure\Console\ConsoleModuleInterface;
use Slick\ModuleApi\Infrastructure\FrontController\MiddlewareHandler;
use Slick\ModuleApi\Infrastructure\FrontController\MiddlewarePosition;
use Slick\ModuleApi\Infrastructure\FrontController\Position;
use Slick\ModuleApi\Infrastructure\FrontController\WebModuleInterface;
use function Slick\ModuleApi\importSettingsFile;

/**
 * JsonApiModule
 *
 * @package Slick\JSONAPI
 */
final class JsonApiModule extends AbstractModule implements WebModuleInterface, ConsoleModuleInterface
{
    public function name(): string
    {
        return 'json-api';
    }

    public function description(): ?string
    {
        return "JSON:API 1.1 specification encode and decode support.";
    }


    public function services(): array
    {
        $servicesFile = dirname(__DIR__) . '/config/services.php';
        return importSettingsFile($servicesFile);
    }

    public function middlewareHandlers(): array
    {
        return [
            new MiddlewareHandler(
                'json-api',
                new MiddlewarePosition(Position::Before, 'router'),
                JsonApiParserMiddleware::class
            ),
        ];
    }
}
