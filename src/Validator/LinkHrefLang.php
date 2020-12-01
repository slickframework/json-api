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
use Slick\JSONAPI\Validator;

/**
 * LinkHrefLang
 *
 * @package Slick\JSONAPI\Validator
 */
final class LinkHrefLang implements Validator
{

    /** @var Collection */
    private static $languages;

    /**
     * @inheritDoc
     */
    public function isValid($subject, $context = null): bool
    {
        return $this->languages()->containsKey($subject);
    }

    /**
     * Current languages
     *
     * @return Collection
     */
    private function languages(): Collection
    {
        if (!self::$languages) {
            self::$languages = $this->loadData();
        }

        return self::$languages;
    }

    /**
     * Loads data from JSON file.
     *
     * @return Collection
     */
    private function loadData(): Collection
    {
        $data = json_decode(file_get_contents(__DIR__.'/data/languages.json'));
        $languages = new ArrayCollection();
        foreach ($data as $code => $name) {
            $languages->set($code, $name);
        }
        return $languages;
    }
}
