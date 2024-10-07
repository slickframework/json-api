<?php

/**
 * This file is part of json-api
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Config\Services;

use Slick\Configuration\ConfigurationInterface;
use Slick\Di\Container;
use Slick\Di\Definition\ObjectDefinition;
use Slick\JSONAPI\Document\Converter\PHPJson;
use Slick\JSONAPI\Document\Decoder\DefaultDecoder;
use Slick\JSONAPI\Document\DocumentConverter;
use Slick\JSONAPI\Document\DocumentDecoder;
use Slick\JSONAPI\Document\DocumentEncoder;
use Slick\JSONAPI\Document\DocumentFactory;
use Slick\JSONAPI\Document\Encoder\DefaultEncoder;
use Slick\JSONAPI\Document\Factory\DefaultFactory;
use Slick\JSONAPI\Document\Factory\SparseFields;
use Slick\JSONAPI\Document\HttpMessageParser;
use Slick\JSONAPI\JsonApi;
use Slick\JSONAPI\JsonApiParserMiddleware;
use Slick\JSONAPI\Object\SchemaDiscover;
use Slick\JSONAPI\Validator\SchemaValidator;

$discover = '@json:api.schema.discover';
$services = [];

// -- Document encoder
$services[DocumentEncoder::class] = '@json:api.document.encoder';
$services['json:api.document.encoder'] = function (Container $container) {
    $encoder = new DefaultEncoder(
        $container->get('json:api.schema.discover'),
        $container->get('json:api.document.factory'),
        $container->get('json:api.document.converted')
    );
    $server = $container->get(ConfigurationInterface::class)->get('server', 'http://localhost');
    return $encoder
        ->withJsonapi(new JsonApi(JsonApi::JSON_API_11))
        ->withLinkPrefix($server)
        ;
};

// -- Document decoder
$services[DocumentDecoder::class] = '@json:api.document.decoder';
$services['json:api.document.decoder'] = ObjectDefinition
    ::create(DefaultDecoder::class)
    ->with($discover, '@json:api.schema.decode.validator');

// -- Document decoder HTTP middleware
$services[JsonApiParserMiddleware::class] = '@json:api.parser.middleware';
$services['json:api.parser.middleware'] = ObjectDefinition
    ::create(JsonApiParserMiddleware::class)
    ->with('@json:api.document.decoder', new HttpMessageParser());


// -- Document factory
$services[DocumentFactory::class] = "@json:api.document.factory";
$services['json:api.document.factory'] = ObjectDefinition
    ::create(DefaultFactory::class)
    ->with($discover)
    ->call('withSparseFields')->with('@sparse.fields');

// -- sparse fields
$services[SparseFields::class] = '@sparse.fields';
$services['sparse.fields'] = ObjectDefinition::create(SparseFields::class)
    ->with('@http.request');

// -- Schema discover
$services[SchemaDiscover::class] = $discover;
$services['json:api.schema.discover'] = ObjectDefinition
    ::create(SchemaDiscover\AttributeSchemaDiscover::class);

$services['json:api.schema.decode.validator'] = ObjectDefinition
    ::create(SchemaValidator::class);

// -- Document converter
$services[DocumentConverter::class] = '@json:api.document.converter';
$services['json:api.document.converted'] = ObjectDefinition
    ::create(PHPJson::class);

return $services;
