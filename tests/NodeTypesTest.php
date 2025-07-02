<?php declare(strict_types=1);

namespace Tests;

use AhjDev\PhpTagMaker\Node\EscapedText;
use AhjDev\PhpTagMaker\Node\HtmlTag;
use AhjDev\PhpTagMaker\Node\HtmlTagMulti;
use AhjDev\PhpTagMaker\Node\HtmlText;
use AhjDev\PhpTagMaker\TagMaker;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the different Node types (HtmlText, EscapedText, HtmlTagMulti).
 *
 * @covers \AhjDev\PhpTagMaker\Node\HtmlText
 * @covers \AhjDev\PhpTagMaker\Node\EscapedText
 * @covers \AhjDev\PhpTagMaker\Node\HtmlTagMulti
 */
final class NodeTypesTest extends TestCase
{
    /**
     * Tests that HtmlText correctly escapes special HTML characters when rendered.
     */
    public function testHtmlTextRendersAndEscapesCorrectlyInParent(): void
    {
        $tag = HtmlTag::p(new HtmlText('5 > 3 & 2 < 4'));
        $output = TagMaker::build($tag);

        // DOMDocument will escape '<', '>', and '&'.
        $expected = '<p>5 &gt; 3 &amp; 2 &lt; 4</p>';
        $this->assertXmlStringEqualsXmlString($expected, $output);
    }

    /**
     * Tests that EscapedText correctly creates a CDATA section, preventing
     * the content from being parsed by the HTML parser.
     */
    public function testEscapedTextCreatesCdataNodeInParent(): void
    {
        // FIX: The first argument to `div()` is reserved for classes.
        // Pass `null` as the first argument to specify no class, and the
        // EscapedText node as the second argument (a child).
        $tag = HtmlTag::div(null, new EscapedText('if (a < b && b > c) { /* code */ }'));

        $domNode = $tag->toDomNode();

        $this->assertTrue($domNode->hasChildNodes());
        $this->assertEquals(1, $domNode->childNodes->length);
        $firstChild = $domNode->firstChild;
        $this->assertInstanceOf(\DOMCdataSection::class, $firstChild);
        $this->assertEquals('if (a < b && b > c) { /* code */ }', $firstChild->nodeValue);
    }

    public function testHtmlTagMultiCreatesNestedStructure(): void
    {
        $multiTag = new HtmlTagMulti(['div', 'p', 'strong'], 'Deep Text');
        $output = TagMaker::build($multiTag);

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
        $output = TagMaker::build($multiTag);

        $expected = '<section><article><b>Title</b> and text</article></section>';
        $this->assertXmlStringEqualsXmlString($expected, $output);
    }
}
