<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Document;

use Slick\JSONAPI\Document;
use Slick\JSONAPI\Document\ErrorDocument;
use PhpSpec\ObjectBehavior;
use Slick\JSONAPI\JsonApi;
use Slick\JSONAPI\Object\ErrorObject;
use Slick\JSONAPI\Object\ResourceCollection;

/**
 * ErrorDocumentSpec specs
 *
 * @package spec\Slick\JSONAPI\Document
 */
class ErrorDocumentSpec extends ObjectBehavior
{

    private $errorObject;
    private $jsonApi;
    private $errors;

    function let()
    {
        $this->errorObject = new ErrorObject('Some Error');
        $this->jsonApi = new JsonApi(JsonApi::JSON_API_11);
        $this->errors = new ResourceCollection('errors', [$this->errorObject]);
        $this->beConstructedWith($this->errors, $this->jsonApi);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ErrorDocument::class);
    }

    function its_a_document()
    {
        $this->shouldBeAnInstanceOf(Document::class);
    }

    function it_has_a_jsonApi()
    {
        $this->jsonapi()->shouldBe($this->jsonApi);
    }

    function it_has_a_errors()
    {
        $this->errors()->shouldBe($this->errors);
    }

    function it_can_be_converted_to_json()
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            'jsonapi' => $this->jsonApi,
            'errors' => $this->errors
        ]);
    }
}