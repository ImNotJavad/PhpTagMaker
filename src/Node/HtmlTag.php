<?php declare(strict_types=1);

namespace AhjDev\PhpTagMaker\Node;

use DOMElement;
use AhjDev\PhpTagMaker\Node;
use AhjDev\PhpTagMaker\HtmlClass;

/**
 * Represents a single HTML element.
 *
 * This is the core class for building HTML structures, providing methods
 * for attribute management, child manipulation, and rendering to a DOM node.
 */
final class HtmlTag extends Node
{
    use Internal\Attributes;
    use Internal\DefaultTags;

    /** @var list<Node> */
    private array $values = [];

    public HtmlClass $class;

    private array $attributes = [];

    public function __construct(private string $tag, Node|string ...$value)
    {
        $this->values = array_map(static fn ($v) => is_string($v) ? new HtmlText($v) : $v, $value);
        $this->class = new HtmlClass;
    }

    public static function make(string $tag, Node|string ...$value): self
    {
        return new self($tag, ...$value);
    }

    public function getName(): string
    {
        return $this->tag;
    }

    /**
     * Summary of setName
     * @param string $newTagName
     * @return Node\HtmlTag
     */
    public function setName(string $newTagName): self
    {
        $this->tag = $newTagName;
        return $this;
    }
    /**
     * Appends a child Node or string to this tag.
     * If a string is provided, it will be wrapped in an HtmlText node.
     *
     * @param Node|string $child The child to append.
     * @return self Returns the instance for method chaining.
     */
    public function appendChild(Node|string $child): self
    {
        $node = is_string($child) ? new HtmlText($child) : $child;
        $this->values[] = $node;
        return $this;
    }

    public function prependChild(Node|string $child): self
    {
        $node = is_string($child) ? new HtmlText($child) : $child;
        array_unshift($this->values, $node);
        return $this;
    }

    public function toDomNode(?\DOMDocument $doc = null): DOMElement
    {
        $document = $doc ?? new \DOMDocument();
        $element = $document->createElement($this->tag);

        foreach ($this->attributes as $name => $value) {
            $element->setAttribute($name, $value);
        }

        if ($this->class->count() > 0) {
            $element->setAttribute('class', (string) $this->class);
        }

        foreach ($this->values as $valueNode) {
            $childDomNode = $valueNode->toDomNode($document);
            if ($childDomNode->ownerDocument !== $document) {
                $childDomNode = $document->importNode($childDomNode, true);
            }
            $element->appendChild($childDomNode);
        }
        return $element;
    }
}