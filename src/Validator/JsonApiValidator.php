<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Validator;

use Slick\JSONAPI\Exception\UnknownValidator;
use Slick\JSONAPI\Validator;

/**
 * JsonApiValidator
 *
 * @package Slick\JSONAPI\Validator
 */
final class JsonApiValidator implements Validator
{

    const VALIDATE_MEMBER_NAME = 0;
    const VALIDATE_LINK_REL    = self::VALIDATE_MEMBER_NAME + 1;

    /** @var array<int, string> */
    private static $map = [
        self::VALIDATE_MEMBER_NAME => MemberName::class,
        self::VALIDATE_LINK_REL => LinkRelation::class
    ];

    /** @var array<Validator> */
    private static $instances = [];

    /** @var JsonApiValidator */
    private static $instance;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * Singleton instance creator
     *
     * @return JsonApiValidator
     */
    public static function instance(): JsonApiValidator
    {
        if (!self::$instance) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * @inheritDoc
     */
    public function isValid($subject, $context = null): bool
    {
        $validator = $this->validator($context);
        return $validator->isValid($subject, $context);
    }

    /**
     * Creates a validator for provided context
     *
     * @param mixed $context
     * @return Validator
     * @throws UnknownValidator when cannot infer validator classname from context
     */
    private function validator($context): Validator
    {
        $className = $this->inferClassName($context);

        if (array_key_exists($className, self::$instances)) {
            return self::$instances[$className];
        }

        $validator = new $className();
        self::$instances[$className] = $validator;
        return $validator;
    }

    /**
     * Infer class name from context
     *
     * @param mixed $context
     * @return string
     */
    private function inferClassName($context): string
    {
        if (array_key_exists($context, self::$map)) {
            return self::$map[$context];
        }

        if (class_exists($context) && in_array(Validator::class, class_implements($context))) {
            return $context;
        }

        throw new UnknownValidator(
            "Unknown validator $context."
        );
    }
}
