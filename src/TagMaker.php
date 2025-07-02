<?php declare(strict_types=1);

namespace AhjDev\PhpTagMaker;

use DOMDocument;
use LogicException;

/**
 * The main engine that builds and renders the final HTML string from a Node tree.
 *
 * It uses PHP's DOMDocument internally to ensure the output is well-formed,
 * secure, and standards-compliant.
 */
final class TagMaker
{
    /**
     * @var bool Determines whether the output HTML should be formatted.
     */
    private bool $formatOutput = false;

    /**
     * TagMaker constructor.
     *
     * @throws LogicException if the 'dom' extension is not available.
     */
    public function __construct()
    {
        if (!\extension_loaded('dom')) {
            throw new LogicException('The "dom" extension is required to use PhpTagMaker.');
        }
    }

    /**
     * Configures the output formatting.
     *
     * @param bool $option Set to true to enable formatting.
     */
    public function formatOutput(bool $option = true): self
    {
        $this->formatOutput = $option;
        return $this;
    }

    /**
     * Processes a given Node and generates the corresponding HTML string.
     *
     * @param Node $node The root node of the structure to be rendered.
     * @return string The resulting HTML string. Returns an empty string on failure.
     */
    public function run(Node $node): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = $this->formatOutput;

        $importedNode = $dom->importNode($node->toDomNode(), true);
        $dom->appendChild($importedNode);

        $html = $dom->saveHTML($importedNode);

        // The saveHTML method can return false on error. We handle this
        // by returning an empty string to satisfy the return type.
        return $html === false ? '' : $html;
    }

    /**
     * A static helper method to quickly build HTML from a Node.
     *
     * @param Node $node The root node to render.
     * @param bool $format Whether to format the output HTML.
     */
    public static function build(Node $node, bool $format = false): string
    {
        return (new self())->formatOutput($format)->run($node);
    }
}
