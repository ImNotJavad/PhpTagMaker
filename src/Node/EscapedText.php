<?php declare(strict_types=1);

namespace AhjDev\PhpTagMaker\Node;

use AhjDev\PhpTagMaker\Node;
use DOMCdataSection;
use DOMDocument;

/**
 * Represents a CDATA (Character Data) section.
 *
 * The content within this node is not parsed by the HTML parser. This is useful
 * for embedding content that contains special characters, such as inline
 * JavaScript or XML data, without needing to escape them manually.
 */
final class EscapedText extends Node
{
    /**
     * @var string The content to be wrapped in a CDATA section.
     */
    private string $text;

    /**
     * EscapedText constructor.
     *
     * @param string $text The content to be wrapped in a CDATA section.
     */
    public function __construct(string $text)
    {
        $this->text = $text;
    }

    /**
     * Static factory method for creating an EscapedText instance.
     *
     * @param string $text The content for the CDATA section.
     */
    public static function make(string $text): self
    {
        return new self($text);
    }

    /**
     * Returns the underlying DOMCdataSection node.
     *
     * @param DOMDocument|null $doc The parent DOMDocument.
     */
    public function toDomNode(?DOMDocument $doc = null): DOMCdataSection
    {
        // The DOMDocument context is not needed here either, but we accept it
        // for signature consistency across all Node subclasses.
        return new DOMCdataSection($this->text);
    }
}
