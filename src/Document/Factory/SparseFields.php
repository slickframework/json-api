<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Document\Factory;

use Psr\Http\Message\ServerRequestInterface;

/**
 * SparseFields
 *
 * @package Slick\JSONAPI\Document\Factory
 */
final class SparseFields
{

    /**
     * @var array|null
     */
    private $fields = null;

    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var array|null
     */
    private $includes = null;

    /**
     * Creates a SparseFields
     *
     * @param ServerRequestInterface $request
     */
    public function __construct(?ServerRequestInterface $request = null)
    {
        $this->request = $request;
        if (!$request) {
            return;
        }

        $queryParams = $request->getQueryParams();
        $this->fields = array_key_exists('fields', $queryParams)
            ? $this->parseFields($queryParams['fields'])
            : null;
        $this->includes = array_key_exists('include', $queryParams)
            ? $queryParams['include']
            :null;
    }

    /**
     * Checks if requests has fields param
     *
     * @return bool
     */
    public function hasFields(): bool
    {
        return $this->fields != null;
    }

    /**
     * Returns the list of fields defined for a given type
     *
     * @param string $type
     * @return array|null
     */
    public function fieldsFor(string $type): ?array
    {
        if (!$this->hasFields() ||
            !array_key_exists($type, $this->fields)
        ) {
            return null;
        }

        return $this->fields[$type];
    }

    /**
     * Parses fields fieldset list
     *
     * @param array $fields
     * @return array
     */
    private function parseFields(array $fields): array
    {
        $data = [];
        foreach ($fields as $type => $fieldset) {
            $data[$type] = explode(',', trim(str_replace(' ', '', $fieldset)));
        }
        return $data;
    }

    /**
     * Filters out the attributes that aren't in the fields set in the HTTP request
     *
     * @param string $type
     * @param array|null $attributes
     * @return array
     */
    public function filterFields(string $type, ?array $attributes): array
    {
        if (!$this->hasFields()) {
            return $attributes ?? [];
        }

        $fields = $this->fieldsFor($type);
        if (!$fields) {
            return [];
        }

        $data = [];
        $keys = array_keys($attributes);
        foreach ($fields as $field) {
            if (!in_array($field, $keys)) {
                continue;
            }

            $data[$field] = $attributes[$field];
        }

        return $data;
    }

    /**
     * Check if it should include a resource of a given type
     *
     * @param string $type
     * @return bool
     */
    public function includeResource(string $type): bool
    {
        if (!$this->hasFields()) {
            return true;
        }

        return (bool) $this->fieldsFor($type);
    }

    /**
     * Checks if a field is to be included
     *
     * @param string $name
     * @param string $type
     * @return bool
     */
    public function includeField(string $name, string $type): bool
    {
        if (!$this->includeResource($type)) {
            return false;
        }

        $fields = $this->fieldsFor($type);

        return in_array($name, $fields);
    }

    /**
     * Returns the list of types to include
     *
     * @return array|null
     */
    public function includedTypes(): ?array
    {
        return $this->includes;
    }

    /**
     * request
     *
     * @return ServerRequestInterface
     */
    public function request(): ServerRequestInterface
    {
        return $this->request;
    }
}
