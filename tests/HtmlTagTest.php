<?php declare(strict_types=1);

namespace Tests;

use AhjDev\PhpTagMaker\Node\HtmlTag;
use AhjDev\PhpTagMaker\TagMaker;
use LogicException;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the HtmlTag class.
 *
 * @covers \AhjDev\PhpTagMaker\Node\HtmlTag
 * @covers \AhjDev\PhpTagMaker\Node\Internal\Attributes
 * @covers \AhjDev\PhpTagMaker\Node\Internal\DefaultTags
 */
final class HtmlTagTest extends TestCase
{
    public function testBasicTagCreation(): void
    {
        $tag = HtmlTag::div(null, 'Hello World');
        $output = TagMaker::build($tag);
        $this->assertXmlStringEqualsXmlString('<div>Hello World</div>', $output);
    }

    public function testTagCreationWithClass(): void
    {
        $tag = HtmlTag::div('test-class', 'Hello World');
        $output = TagMaker::build($tag);
        $this->assertXmlStringEqualsXmlString('<div class="test-class">Hello World</div>', $output);
    }

    public function testTagCreationWithAttributes(): void
    {
        $tag = HtmlTag::a('https://example.com', 'Click me')
            ->setId('my-link')
            ->setAttribute('target', '_blank');

        $expected = '<a href="https://example.com" id="my-link" target="_blank">Click me</a>';
        $this->assertXmlStringEqualsXmlString($expected, TagMaker::build($tag));
    }

    public function testAppendingChildToTag(): void
    {
        $tag = HtmlTag::ul()->appendChild(HtmlTag::li('Item 1'));
        $this->assertXmlStringEqualsXmlString('<ul><li>Item 1</li></ul>', TagMaker::build($tag));
    }

    public function testPrependingChildToTag(): void
    {
        $list = HtmlTag::ul(HtmlTag::li('Item 2'));
        $list->prependChild(HtmlTag::li('Item 1'));
        $this->assertXmlStringEqualsXmlString('<ul><li>Item 1</li><li>Item 2</li></ul>', TagMaker::build($list));
    }

    public function testChangingTagName(): void
    {
        $element = HtmlTag::div(null, 'Content')->setClass('box');
        $element->setName('section');

        $this->assertXmlStringEqualsXmlString('<section class="box">Content</section>', TagMaker::build($element));
        $this->assertSame('section', $element->getName());
    }

    public function testCannotAddChildToVoidElementOnConstruction(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Cannot add children to a void element <br>.');
        HtmlTag::make('br', 'some text');
    }

    public function testCannotAppendChildToVoidElement(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Cannot add children to a void element <img>.');
        $tag = HtmlTag::img('/cat.jpg');
        $tag->appendChild(HtmlTag::span('A caption'));
    }

    public function testCannotPrependChildToVoidElement(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Cannot add children to a void element <hr>.');
        $tag = HtmlTag::hr();
        $tag->prependChild(HtmlTag::span('A caption'));
    }

    /**
     * FIX: This test now inspects the DOM attributes directly instead of comparing strings.
     * This is more robust and avoids HTML vs. XML parsing issues.
     */
    public function testBooleanAttributeHandling(): void
    {
        $input = HtmlTag::input('checkbox')->checked()->disabled();

        // Convert the tag to a DOMElement to inspect its properties.
        $domElement = $input->toDomNode();

        // Assert that the attributes exist and have the correct values.
        $this->assertTrue($domElement->hasAttribute('checked'));
        $this->assertEquals('checked', $domElement->getAttribute('checked'));
        $this->assertTrue($domElement->hasAttribute('disabled'));
        $this->assertEquals('disabled', $domElement->getAttribute('disabled'));

        // Test removing the attribute.
        $input->disabled(false);
        $domElementAfterRemove = $input->toDomNode();

        // Assert that 'disabled' is now gone, but 'checked' remains.
        $this->assertTrue($domElementAfterRemove->hasAttribute('checked'));
        $this->assertFalse($domElementAfterRemove->hasAttribute('disabled'));
    }
}
