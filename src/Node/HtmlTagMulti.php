<?php declare(strict_types=1);

namespace AhjDev\PhpTagMaker\Node;

use AhjDev\PhpTagMaker\Node;
use DOMDocument;
use IteratorAggregate;
use Traversable;

/**
 * A utility node for creating a deeply nested structure of tags.
 *
 * @implements IteratorAggregate<int, string>
 */
final class HtmlTagMulti extends Node implements IteratorAggregate
{
    /** @var list<Node> The content to be placed at the deepest level of the nested structure. */
    private array $values = [];

    /** @var list<string> The list of tag names to be nested, from outermost to innermost. */
    private array $tags;

    /**
     * HtmlTagMulti constructor.
     *
     * @param list<string> $tags An array of tag names to nest.
     * @param Node|string ...$value The content to be wrapped by the nested tags.
     */
    public function __construct(array $tags, Node|string ...$value)
    {
        $this->tags = $tags;

        // FIX: Wrap the result of array_map in array_values().
        // This guarantees that the resulting array is a `list` (numerically indexed array),
        // which satisfies the strict type definition of the `$values` property.
        $this->values = \array_values(\array_map(
            static fn ($v) => \is_string($v) ? new HtmlText($v) : $v,
            $value
        ));
    }

    /**
     * Static factory method for creating an HtmlTagMulti instance.
     *
     * @param list<string> $tags An array of tag names.
     * @param Node|string ...$value The content.
     */
    public static function make(array $tags, Node|string ...$value): self
    {
        return new self($tags, ...$value);
    }

    /**
     * Returns an iterator for the tag names.
     *
     * @return Traversable<int, string>
     */
    public function getIterator(): Traversable
    {
        yield from $this->tags;
    }

    /**
     * Builds the nested DOM structure.
     *
     * @param DOMDocument|null $doc The parent DOMDocument.
     * @return \DOMNode The outermost DOMNode of the nested structure.
     */
    public function toDomNode(?DOMDocument $doc = null): \DOMNode
    {
        $document = $doc ?? new DOMDocument();

        if (empty($this->tags)) {
            $fragment = $document->createDocumentFragment();
            foreach ($this->values as $value) {
                $fragment->appendChild($value->toDomNode($document));
            }
            return $fragment;
        }

        $currentNode = null;
        $contentNodes = $this->values;

        foreach (\array_reverse($this->tags) as $tagName) {
            $currentNode = new HtmlTag($tagName, ...$contentNodes);
            $contentNodes = [$currentNode];
        }

        // $currentNode will never be null here if $this->tags is not empty.
        // We can safely call toDomNode on it.
        return $currentNode->toDomNode($document);
    }
}
