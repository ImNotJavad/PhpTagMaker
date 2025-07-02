<?php

// This example demonstrates how to format the HTML output for readability
// and showcases the different types of nodes available.

require __DIR__ . '/../vendor/autoload.php';

use AhjDev\PhpTagMaker\TagMaker;
use AhjDev\PhpTagMaker\Node\HtmlTag;
use AhjDev\PhpTagMaker\Node\HtmlText;
use AhjDev\PhpTagMaker\Node\EscapedText;
use AhjDev\PhpTagMaker\Node\HtmlTagMulti;

// 1. Create an instance of the TagMaker.
$maker = new TagMaker();

// 2. Enable output formatting. This adds indentation and newlines.
// This is great for development but should be disabled in production.
$maker->formatOutput(true);

// 3. Build a complex HTML structure.
$output = $maker->run(
    HtmlTag::div('wrapper',
        HtmlTag::h1('Demonstration of Node Types'),

        // A standard link.
        HtmlTag::a('https://github.com/ahjdev/phptagmaker', 'Project on GitHub'),

        // An unordered list with children.
        HtmlTag::ul(
            HtmlTag::li('First item'),
            HtmlTag::li('Second item'),
            HtmlTag::li('Third item')->setClass('special-item')
        ),

        // Using HtmlText to explicitly create a text node.
        // The '<' and '>' will be escaped automatically to '&lt;' and '&gt;'.
        HtmlTag::p(
            HtmlText::make('This text contains special characters like < and >.')
        ),

        // Using EscapedText to create a CDATA section.
        // The content inside will NOT be parsed by the browser.
        // Useful for inline scripts or style blocks.
        HtmlTag::script(
            EscapedText::make("if (x < 5 && y > 2) { console.log('CDATA works!'); }")
        ),

        // Using HtmlTagMulti to create a deeply nested structure easily.
        HtmlTag::p('A multi-tag structure:'),
        HtmlTagMulti::make(
            ['div', 'blockquote', 'p', 'strong'],
            'This text is deeply nested.'
        )
    )
);

// 4. Print the formatted HTML.
print($output);
