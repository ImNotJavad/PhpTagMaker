<?php declare(strict_types=1);

namespace AhjDev\PhpTagMaker\Node;

use AhjDev\PhpTagMaker\HtmlClass;
use AhjDev\PhpTagMaker\Node;
use DOMElement;
use LogicException;

/**
 * Represents a single HTML element (e.g., <div>, <p>, <a>).
 *
 * This is the core class for building HTML structures, providing fluent methods
 * for attribute management, child manipulation, and rendering to a DOM node.
 */
final class HtmlTag extends Node
{
    use Internal\Attributes;
    use Internal\DefaultTags;

    /**
     * @var string The name of the HTML tag (e.g., 'div', 'p').
     */
    private string $tag;

    /**
     * @var Node[] A list of child nodes (HtmlTag, HtmlText, etc.).
     */
    private array $values = [];

    /**
     * A set of HTML5 tags that cannot have any content (e.g., <br>, <img>).
     * @var string[]
     */
    private const VOID_ELEMENTS = [
        'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input',
        'link', 'meta', 'param', 'source', 'track', 'wbr'
    ];

    /**
     * HtmlTag constructor.
     *
     * @param string $tag The name of the HTML tag.
     * @param Node|string ...$value Initial children for the tag. Strings are auto-converted to HtmlText nodes.
     */
    public function __construct(string $tag, Node|string ...$value)
    {
        $this->tag = $tag;
        $this->class = new HtmlClass(); // Initialize the class manager.
        $this->attributes = [];       // Initialize attributes array.

        // Append initial children, checking for void elements.
        foreach ($value as $v) {
            $this->appendChild($v);
        }
    }

    /**
     * Static factory method for creating an HtmlTag instance.
     *
     * @param string $tag The name of the HTML tag.
     * @param Node|string ...$value Initial children for the tag.
     */
    public static function make(string $tag, Node|string ...$value): self
    {
        return new self($tag, ...$value);
    }

    /**
     * Gets the name of the tag.
     *
     */
    public function getName(): string
    {
        return $this->tag;
    }

    /**
     * Changes the name of the tag.
     *
     * Attributes and children are preserved.
     *
     * @param string $newTagName The new tag name (e.g., 'section').
     * @return self The current instance for method chaining.
     */
    public function setName(string $newTagName): self
    {
        $this->tag = $newTagName;
        return $this;
    }

    /**
     * Appends a child Node to this tag.
     *
     * Strings are automatically wrapped in an HtmlText node.
     *
     * @param Node|string $child The child to append.
     * @return self The current instance for method chaining.
     * @throws LogicException If attempting to add a child to a void element.
     */
    public function appendChild(Node|string $child): self
    {
        if ($this->isVoidElement()) {
            throw new LogicException("Cannot add children to a void element <{$this->tag}>.");
        }
        $this->values[] = \is_string($child) ? new HtmlText($child) : $child;
        return $this;
    }

    /**
     * Prepends a child Node to this tag.
     *
     * Strings are automatically wrapped in an HtmlText node.
     *
     * @param Node|string $child The child to prepend.
     * @return self The current instance for method chaining.
     * @throws LogicException If attempting to add a child to a void element.
     */
    public function prependChild(Node|string $child): self
    {
        if ($this->isVoidElement()) {
            throw new LogicException("Cannot add children to a void element <{$this->tag}>.");
        }
        \array_unshift($this->values, \is_string($child) ? new HtmlText($child) : $child);
        return $this;
    }

    /**
     * Converts the HtmlTag instance to a DOMElement.
     *
     * This method builds the element, sets its attributes, and appends all its children recursively.
     *
     * @param \DOMDocument|null $doc The parent DOMDocument, if available.
     */
    public function toDomNode(?\DOMDocument $doc = null): DOMElement
    {
        $document = $doc ?? new \DOMDocument();
        $element = $document->createElement($this->tag);

        // Set all generic attributes.
        foreach ($this->attributes as $name => $value) {
            $element->setAttribute($name, (string) $value);
        }

        // Set the class attribute if any classes are present.
        if ($this->class->count() > 0) {
            $element->setAttribute('class', (string) $this->class);
        }

        // Recursively append all child nodes, but only if it's not a void element.
        if (!$this->isVoidElement()) {
            foreach ($this->values as $valueNode) {
                // Import the node if it belongs to a different document context.
                $childDomNode = $valueNode->toDomNode($document);
                if ($childDomNode->ownerDocument !== $document) {
                    $childDomNode = $document->importNode($childDomNode, true);
                }
                $element->appendChild($childDomNode);
            }
        }

        return $element;
    }

    /**
     * Checks if the current tag is a void element.
     *
     */
    private function isVoidElement(): bool
    {
        return \in_array(\strtolower($this->tag), self::VOID_ELEMENTS, true);
    }
}
