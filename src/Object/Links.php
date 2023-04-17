<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Object;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use IteratorAggregate;
use JsonSerializable;
use Slick\JSONAPI\Object\Link\LinkObject;
use Traversable;

/**
 * Links
 *
 * @package Slick\JSONAPI\Object
 */
final class Links implements IteratorAggregate, JsonSerializable
{
    public const LINK_SELF = 'self';
    public const LINK_RELATED = 'related';

    /**
     * @var string|null
     */
    private ?string $linkPrefix;

    /**
     * @var Collection<string, LinkObject>|ArrayCollection<string, LinkObject>
     */
    private Collection|ArrayCollection $links;

    /**
     * Creates a Links
     *
     * @param string|null $linkPrefix
     */
    public function __construct(?string $linkPrefix = null)
    {
        $this->linkPrefix = $linkPrefix;
        $this->links = new ArrayCollection();
    }

    /**
     * Add a link to the links collection
     *
     * @param string|LinkObject $relOrName
     * @param string|LinkObject|null $href
     *
     * @return Links
     */
    public function add(string|LinkObject $relOrName, string|LinkObject|null $href = null): Links
    {
        if (is_a($relOrName, LinkObject::class)) {
            $this->links->set($relOrName->rel(), $this->verifyLink($relOrName));
            return $this;
        }

        $link = is_a($href, LinkObject::class)
            ? $this->verifyLink($href)
            : $this->createLinkObject($relOrName, $href);

        $this->links->set($relOrName, $link);
        return $this;
    }

    /**
     * Get the link or list of links of a given link relation
     *
     * @param string $linkRelation
     * @return LinkObject|null
     */
    public function get(string $linkRelation): ?LinkObject
    {
        return $this->links->get($linkRelation);
    }

    /**
     * Removes the link stored with provided link relation
     *
     * @param string $linkRelation
     * @return Links
     */
    public function remove(string $linkRelation): Links
    {
        $this->links->remove($linkRelation);
        return $this;
    }

    /**
     * createLinkObject
     *
     * @param string $rel
     * @param string $href
     * @return LinkObject
     */
    private function createLinkObject(string $rel, string $href): LinkObject
    {
        $validUrl = (bool) filter_var($href, FILTER_VALIDATE_URL);
        if ($this->linkPrefix && !$validUrl) {
            $href = rtrim($this->linkPrefix, ' /').$href;
        }
        return new LinkObject($href, $rel);
    }

    /**
     * Verifies Link's href and adds link prefix if necessary
     *
     * Object with a full valid URI will not be changed.
     *
     * @param LinkObject $linkObject
     * @return LinkObject
     */
    private function verifyLink(LinkObject $linkObject): LinkObject
    {
        $validUrl = (bool) filter_var($linkObject->href(), FILTER_VALIDATE_URL);
        if (!$this->linkPrefix || $validUrl) {
            return $this->verifyDescribedBy($linkObject);
        }

        return $this->verifyDescribedBy(
            $linkObject->withHref(rtrim($this->linkPrefix, ' /').$linkObject->href())
        );
    }

    private function verifyDescribedBy(LinkObject $object): LinkObject
    {
        $validUrl = (bool) filter_var($object->describedBy(), FILTER_VALIDATE_URL);
        if (!$object->describedBy() || $validUrl) {
            return $object;
        }

        return $object->withDescribedBy(rtrim($this->linkPrefix, ' /').$object->describedBy());
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        return $this->links->getIterator();
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        $data = [];
        foreach ($this as $rel => $link) {
            $data[$rel] = $link;
        }
        return $data;
    }

    /**
     * checkLinks
     *
     * @param Links $links
     * @param ?string $linkPrefix
     * @return Links
     */
    public static function checkLinks(Links $links, ?string $linkPrefix = null): Links
    {
        if (!$linkPrefix) {
            return $links;
        }

        $newLinks = new Links($linkPrefix);
        foreach ($links as $rel => $link) {
            $newLinks->add($rel, $link->href());
        }
        return $newLinks;
    }
}
