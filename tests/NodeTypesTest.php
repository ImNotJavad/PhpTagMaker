<?php declare(strict_types=1);

namespace Tests;

use AhjDev\PhpTagMaker\Node\EscapedText;
use AhjDev\PhpTagMaker\Node\HtmlTag;
use AhjDev\PhpTagMaker\Node\HtmlTagMulti;
use AhjDev\PhpTagMaker\Node\HtmlText;
use PHPUnit\Framework\TestCase;

final class NodeTypesTest extends TestCase
{
    public function testHtmlTextRendersAndEscapesCorrectlyInParent(): void
    {
        $tag = HtmlTag::make('p', new HtmlText('5 > 3'));

        $node = $tag->toDomNode();
        $output = $node->ownerDocument->saveHTML($node);

        $expected = '<p>5 &gt; 3</p>';
        $this->assertXmlStringEqualsXmlString($expected, $output);
    }

    /**
     * This is the corrected test for CDATA sections.
     * Instead of comparing strings, we inspect the DOM structure directly.
     */
    public function testEscapedTextCreatesCdataNodeInParent(): void
    {
        // Arrange
        $tag = HtmlTag::make('div', new EscapedText('if (a < b) {}'));

        // Act
        $domNode = $tag->toDomNode();

        // Assert
        // 1. Check that the div has exactly one child node.
        $this->assertTrue($domNode->hasChildNodes());
        $this->assertEquals(1, $domNode->childNodes->length);

        // 2. Get the first child.
        $firstChild = $domNode->firstChild;

        // 3. Assert that the child is a CDATA Section node.
        $this->assertInstanceOf(\DOMCdataSection::class, $firstChild);

        // 4. Assert that the content of the CDATA node is correct.
        $this->assertEquals('if (a < b) {}', $firstChild->nodeValue);
    }

    public function testHtmlTagMultiCreatesNestedStructure(): void
    {
        $multiTag = new HtmlTagMulti(['div', 'p', 'strong'], 'Deep Text');

        $node = $multiTag->toDomNode();
        $output = $node->ownerDocument->saveHTML($node);

        $expected = '<div><p><strong>Deep Text</strong></p></div>';
        $this->assertXmlStringEqualsXmlString($expected, $output);
    }

    public function testHtmlTagMultiWithNodeChildren(): void
    {
        $multiTag = new HtmlTagMulti(
            ['section', 'article'],
            HtmlTag::b('Title'),
            ' and text'
        );

        $node = $multiTag->toDomNode();
        $output = $node->ownerDocument->saveHTML($node);

        $expected = '<section><article><b>Title</b> and text</article></section>';
        $this->assertXmlStringEqualsXmlString($expected, $output);
    }
}