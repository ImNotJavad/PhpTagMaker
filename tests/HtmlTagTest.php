<?php declare(strict_types=1);

namespace Tests;

use AhjDev\PhpTagMaker\Node\HtmlTag;
use PHPUnit\Framework\TestCase;

final class HtmlTagTest extends TestCase
{
    public function testBasicTagCreation(): void
    {
        $tag = HtmlTag::div('test-class', 'Hello World');
        $node = $tag->toDomNode();
        $output = $node->ownerDocument->saveHTML($node);
        $expected = '<div class="test-class">Hello World</div>';
        $this->assertXmlStringEqualsXmlString($expected, $output);
    }
}