<?php

// This example demonstrates advanced features like attribute manipulation,
// dynamic child management, and changing tag names.

require __DIR__ . '/../vendor/autoload.php';

use AhjDev\PhpTagMaker\TagMaker;
use AhjDev\PhpTagMaker\Node\HtmlTag;

// Initialize TagMaker with output formatting enabled for clarity.
$maker = new TagMaker();
$maker->formatOutput(true);

echo "<h2>Advanced Tag Features</h2>\n\n";

// --- 1. Boolean, Data, and ARIA Attributes ---
echo "<h3>1. Input with Boolean, Data, and ARIA Attributes:</h3>\n";
$input = HtmlTag::input('checkbox')
    ->setId('subscribe-checkbox')
    ->setDataAttribute('item-id', 'A123')
    ->setAriaAttribute('label', 'Subscribe to newsletter')
    ->checked()  // Sets 'checked="checked"'
    ->disabled(); // Sets 'disabled="disabled"'

echo $maker->run($input);
echo "<hr>\n";


// --- 2. Appending and Prepending Children ---
echo "<h3>2. List with Appended and Prepended Children:</h3>\n";
$list = HtmlTag::ul()->addClass('task-list');

// Add children after the object has been created.
$list->appendChild(HtmlTag::li('Second item, added via appendChild'));
$list->prependChild(HtmlTag::li('First item, added via prependChild'));
$list->appendChild(HtmlTag::li('Third item'));

echo $maker->run($list);
echo "<hr>\n";


// --- 3. Changing Tag Name with setName() ---
echo "<h3>3. Changing Tag Name (setName):</h3>\n";
$contentBlock = HtmlTag::div(
    'initial-class',
    HtmlTag::p('This is a paragraph inside the original div.')
)->setId('content-block-1');

echo "<h4>Original div:</h4>\n";
echo $maker->run($contentBlock);

// Now, transform the <div> into an <article>
$contentBlock->setName('article');
$contentBlock->addClass('important-article'); // Add another class
$contentBlock->appendChild(HtmlTag::footer('End of article.')); // Add a new child

echo "\n<h4>Changed to article (attributes and children preserved/updated):</h4>\n";
echo $maker->run($contentBlock);
echo "<hr>\n";


// --- 4. Toggling Classes ---
echo "<h3>4. Toggling CSS Classes:</h3>\n";
$panel = HtmlTag::div('panel')->setId('info-panel');
echo "Initial panel: " . $maker->run($panel) . "\n";

// Add 'visible' and 'active' classes
$panel->toggleClass('visible', 'active');
echo "Panel after toggling 'visible' and 'active': " . $maker->run($panel) . "\n";

// Remove 'active' class by toggling it again
$panel->toggleClass('active');
echo "Panel after toggling 'active' again: " . $maker->run($panel) . "\n";
echo "<hr>\n";
