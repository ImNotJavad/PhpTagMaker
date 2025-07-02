<?php

// This example demonstrates the most basic usage of the PhpTagMaker library.

require __DIR__ . '/../vendor/autoload.php';

use AhjDev\PhpTagMaker\TagMaker;
use AhjDev\PhpTagMaker\Node\HtmlTag;

// Use the static `build` method for a quick one-liner.
// The first argument is the root Node of your HTML structure.
$output = TagMaker::build(
    // Create a <div> tag using the static helper method.
    HtmlTag::div(
        // The first argument to div() can be a string of CSS classes.
        'container main-content',
        
        // Children can be other HtmlTag nodes.
        HtmlTag::h1('Welcome to PhpTagMaker!'),
        HtmlTag::p(
            'This is a simple paragraph created with the fluent API.'
        )
    )
);

// Print the generated HTML string.
// Output will be: <div class="container main-content"><h1>Welcome to PhpTagMaker!</h1><p>This is a simple paragraph created with the fluent API.</p></div>
print($output);
