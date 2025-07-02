<?php declare(strict_types=1);

namespace AhjDev\PhpTagMaker\Node;

use AhjDev\PhpTagMaker\Node;
use DOMDocument;
use DOMText;

/**
 * Represents a plain text node within the HTML structure.
 *
 * When rendered, the content of this node will be properly escaped by the
 * underlying DOMDocument to prevent XSS attacks and ensure well-formed HTML.
 */
final class HtmlText extends Node
{
    /**
     * @var string The raw text content for this node.
     */
    private string $text;

    /**
     * HtmlText constructor.
     *
     * @param string $text The raw text content for this node.
     */
    public function __construct(string $text)
    {
        $this->text = $text;
    }

    /**
     * Static factory method for creating an HtmlText instance.
     *
     * @param string $text The raw text content.
     */
    public static function make(string $text): self
    {
        return new self($text);
    }

    /**
     * Returns the underlying DOMText node.
     *
     * @param DOMDocument|null $doc The parent DOMDocument.
     */
    public function toDomNode(?DOMDocument $doc = null): DOMText
    {
        // The DOMDocument context is not strictly needed to create a DOMText,
        // but we accept the parameter to maintain a consistent method signature.
        return new DOMText($this->text);
    }
}
