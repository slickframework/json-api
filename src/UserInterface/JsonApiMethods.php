<?php

/**
 * This file is part of json-api
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Slick\JSONAPI\UserInterface;

use Slick\Di\Definition\Attributes\Autowire;
use Slick\JSONAPI\Document\DocumentDecoder;
use Slick\JSONAPI\Document\DocumentEncoder;
use Slick\JSONAPI\Exception\MissingDependency;

/**
 * JsonApiMethods
 *
 * @package Slick\JSONAPI\UserInterface
 */
trait JsonApiMethods
{
    protected ?DocumentEncoder $encoder = null;

    protected ?DocumentDecoder $decoder = null;

    /**
     * Includes a DocumentEncoder and DocumentDecoder in the JsonApiMethods object
     *
     * @param DocumentEncoder $encoder The DocumentEncoder object to include
     * @param DocumentDecoder $decoder The DocumentDecoder object to include
     * @return $this The updated JsonApiMethods object
     */
    #[Autowire]
    public function with(DocumentEncoder $encoder, DocumentDecoder $decoder): self
    {
        $this->encoder = $encoder;
        $this->decoder = $decoder;
        return $this;
    }

    /**
     * Encodes the given object into a string using the configured encoder.
     *
     * @param mixed $object The object to encode
     *
     * @return string The encoded string representation of the object
     * @throws MissingDependency If the document encoder is not set, indicating a missing dependency
     */
    protected function encode(mixed $object): string
    {
        if (!$this->encoder) {
            throw new MissingDependency(
                "Document encoder not set. Dependency container did not inject dependencies on autowire. ".
                "You may need to set them yourself."
            );
        }
        return $this->encoder->encode($object);
    }

    /**
     * Decode the data to the specified object class name using the decoder.
     *
     * @param string $objectClassName The class name of the object to decode the data to
     * @return mixed The decoded data
     */
    protected function decode(string $objectClassName): mixed
    {
        if (!$this->decoder) {
            throw new MissingDependency(
                "Document decoder not set. Dependency container did not inject dependencies on autowire. ".
                "You may need to set them yourself."
            );
        }

        return $this->decoder->decodeTo($objectClassName);
    }
}
