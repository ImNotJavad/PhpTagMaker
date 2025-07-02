<?php declare(strict_types=1);

namespace AhjDev\PhpTagMaker;

/**
 * Represents the abstract concept of a Node in the HTML structure.
 *
 * All concrete elements that can be rendered, such as HTML tags or text nodes,
 * must extend this class and implement the `toDomNode` method.
 */
abstract class Node
{
    /**
     * Converts the current node into its corresponding DOMNode representation.
     *
     * This method is essential for the TagMaker to build the final HTML
     * string using the underlying DOMDocument.
     *
     * @param \DOMDocument|null $doc The parent DOMDocument, if available, to prevent creating new instances.
     * @return \DOMNode The concrete DOMNode instance (e.g., DOMElement, DOMText).
     */
    abstract public function toDomNode(?\DOMDocument $doc = null): \DOMNode;
}
