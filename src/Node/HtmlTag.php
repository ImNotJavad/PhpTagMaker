<?php declare(strict_types=1);

namespace AhjDev\PhpTagMaker\Node;

use DOMElement;
use AhjDev\PhpTagMaker\Node;
use AhjDev\PhpTagMaker\HtmlClass; //

final class HtmlTag extends Node
{
    use Internal\Attributes; //
    use Internal\DefaultTags; //

    /** @var list<Node> */
    private array $values = [];

    public HtmlClass $class; // Made public for easier access from Attributes trait, or use getters/setters

    private DOMElement $domElement;
    private string $tagName; // Store original tag name if needed, or rely on domElement->nodeName

    public function __construct(private string $tag, Node|string ...$value)
    {
        $this->tagName = $tag; //
        $this->domElement = new DOMElement($this->tagName); //
        $this->values = array_map(static fn ($v) => is_string($v) ? new HtmlText($v) : $v, $value); //
        $this->class = new HtmlClass; //
    }

    public static function make(string $tag, Node|string ...$value): self
    {
        return new self($tag, ...$value);
    }

    public function getName(): string
    {
        return $this->domElement->nodeName; //
    }

    /**
     * Changes the tag name of the element.
     * Attributes and child nodes are preserved.
     * @param string $newTagName The new tag name.
     */
    public function setName(string $newTagName): self
    {
        if ($this->domElement->nodeName === $newTagName) {
            return $this;
        }

        // Create a new DOMElement with the new tag name.
        // We need a document to create elements. If domElement is already part of a document, use that.
        // Otherwise, create a temporary document. This is a bit of a workaround for standalone DOMElements.
        $doc = $this->domElement->ownerDocument ?? new \DOMDocument();
        $newElement = $doc->createElement($newTagName);

        // Copy attributes from the old element to the new element.
        if ($this->domElement->hasAttributes()) {
            foreach ($this->domElement->attributes as $attribute) {
                if ($attribute instanceof \DOMAttr) { // Ensure it's an attribute node
                    $newElement->setAttribute(
                        $attribute->nodeName,
                        $attribute->nodeValue
                    );
                }
            }
        }

        // Move child nodes (from internal $values array) to the new element conceptually.
        // The actual DOM appending happens in toDomNode.
        // No direct DOM child manipulation here if $values is the source of truth for children.

        // Replace the internal domElement.
        $this->domElement = $newElement;
        $this->tagName = $newTagName; // Update internal tag name property

        // The $this->class (HtmlClass instance) and $this->values (child Node instances) remain,
        // and will be applied to the new $this->domElement in the toDomNode method.
        // If class was directly on old domElement and not in $this->class, it's copied above.
        // We ensure $this->class is the source of truth for class attribute.
        if ($this->class->count() > 0) {
            $this->domElement->setAttribute('class', (string)$this->class);
        } else {
            // If the new element might have inherited a class attribute from a cloned old element
            // and our HtmlClass instance is empty, remove it to ensure HtmlClass is authoritative.
            $this->domElement->removeAttribute('class');
        }


        return $this;
    }

    /**
     * Appends a child Node or string to this tag.
     * If a string is provided, it will be wrapped in an HtmlText node.
     * @param Node|string $child The child to append.
     */
    public function appendChild(Node|string $child): self
    {
        $node = is_string($child) ? new HtmlText($child) : $child;
        $this->values[] = $node;
        return $this;
    }

    /**
     * Prepends a child Node or string to this tag.
     * If a string is provided, it will be wrapped in an HtmlText node.
     * @param Node|string $child The child to prepend.
     */
    public function prependChild(Node|string $child): self
    {
        $node = is_string($child) ? new HtmlText($child) : $child;
        array_unshift($this->values, $node);
        return $this;
    }

    /**
     * Converts the HtmlTag object to its DOMElement representation.
     * This method constructs the DOMElement with all its attributes and children.
     * @param \DOMDocument|null $doc The document to create the element in, if null a new one is used for the element.
     * @return DOMElement
     */
    public function toDomNode(?\DOMDocument $doc = null): DOMElement
    {
        // Create a new element or clone the existing one.
        // Cloning `false` means we only get the element itself, not its attributes or children from the current $this->domElement.
        // This is preferred if we are rebuilding it from $this properties.
        $document = $doc ?? $this->domElement->ownerDocument ?? new \DOMDocument();
        $element = $document->createElement($this->domElement->nodeName);


        // Apply attributes directly set on $this->domElement (e.g., by Attribute trait methods)
        // This ensures any ad-hoc attributes set via setAttribute() are preserved.
        if ($this->domElement->hasAttributes()) {
            foreach ($this->domElement->attributes as $attribute) {
                 if ($attribute instanceof \DOMAttr && $attribute->nodeName !== 'class') {
                    $element->setAttribute($attribute->nodeName, $attribute->nodeValue);
                }
            }
        }

        // Apply the class attribute from the HtmlClass instance, which is the source of truth.
        if ($this->class->count() > 0) {
            $element->setAttribute('class', (string) $this->class);
        }


        // Append child nodes from the $this->values array.
        // Each child Node's toDomNode() method will be called.
        foreach ($this->values as $valueNode) {
            $childDomNode = $valueNode->toDomNode($document); // Pass the document context
            // Import node if it belongs to a different document (can happen if nodes are complexly constructed)
            if ($childDomNode->ownerDocument !== $document) {
                 $childDomNode = $document->importNode($childDomNode, true);
            }
            $element->appendChild($childDomNode);
        }
        return $element;
    }
}