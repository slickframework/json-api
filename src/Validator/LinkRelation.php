<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Validator;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use RuntimeException;
use Slick\JSONAPI\Validator;

/**
 * LinkRelation
 *
 * @package Slick\JSONAPI\Validator
 */
final class LinkRelation implements Validator
{

    /** @var Collection */
    private static $data;

    /**
     * @inheritDoc
     */
    public function isValid($subject, $context = null): bool
    {
        return $this->data()->containsKey($subject);
    }

    /**
     * Link relational data
     *
     * @return Collection
     */
    private function data(): Collection
    {
        if (!self::$data) {
            self::$data = $this->loadData();
        }
        return self::$data;
    }

    /**
     * Loads data from source file.
     *
     * @return Collection
     */
    private function loadData(): Collection
    {
        $collection = new ArrayCollection();
        $handle = $this->readFile();

        $headers = [];
        $count = 0;
        while (($data = fgetcsv($handle, 1000)) !== false) {
            if ($count++ === 0) {
                $headers = $data;
                continue;
            }

            $object = $this->createObject($data, $headers);
            $key = reset($object);
            $collection->set($key, (object) $object);
        }

        fclose($handle);
        return $collection;
    }

    /**
     * Fixes name for use as object property
     *
     * @param string $name
     * @return string
     */
    private function fixName(string $name): string
    {
        return lcfirst(str_replace(' ', '', $name));
    }

    /**
     * Creates an object from CSV data row
     *
     * @param array $data
     * @param array $headers
     * @return array
     */
    private function createObject(array $data, array $headers): array
    {
        $object = [];
        foreach ($data as $key => $value) {
            $object[$this->fixName($headers[$key])] = $value;
        }
        return $object;
    }

    /**
     * Reads CSV file load
     *
     * @return resource
     */
    private function readFile()
    {
        $dataFile = __DIR__ . '/data/link-relations-1.csv';
        $handle = fopen($dataFile, 'r');
        if ($handle === false) {
            throw new RuntimeException("Cannot read relations CVS file.");
        }
        return $handle;
    }
}
