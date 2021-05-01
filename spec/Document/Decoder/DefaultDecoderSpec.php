<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Document\Decoder;

use Slick\JSONAPI\Document;
use Slick\JSONAPI\Document\Decoder\DefaultDecoder;
use PhpSpec\ObjectBehavior;
use Slick\JSONAPI\Document\DocumentDecoder;
use Slick\JSONAPI\Object\ResourceObject;
use Slick\JSONAPI\Object\SchemaDiscover;
use Slick\JSONAPI\Validator;
use spec\Slick\JSONAPI\Document\Decoder\Fixtures\CreatePersonCommand;
use spec\Slick\JSONAPI\Document\Decoder\Fixtures\CreatePersonCommandSchema;
use spec\Slick\JSONAPI\Document\Factory\PersonSchema;

/**
 * DefaultDecoderSpec specs
 *
 * @package spec\Slick\JSONAPI\Document\Decoder
 */
class DefaultDecoderSpec extends ObjectBehavior
{

    function let(SchemaDiscover $schemaDiscover, Validator $validator)
    {
        $schemaDiscover->discover(CreatePersonCommand::class)->willReturn(new CreatePersonCommandSchema());
        $this->beConstructedWith($schemaDiscover, $validator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DefaultDecoder::class);
    }

    function its_a_document_decoder()
    {
        $this->shouldBeAnInstanceOf(DocumentDecoder::class);
    }

    function it_decodes_a_document(Document $document, ResourceObject $resource)
    {
        $document->data()->willReturn($resource);
        $resource->attributes()->willReturn(['name' => 'John Doe', 'email' => 'john.doe@example.com']);
        $this->setRequestedDocument($document);
        $command = $this->decodeTo(CreatePersonCommand::class);
        $command->shouldBeAnInstanceOf(CreatePersonCommand::class);
    }
}
