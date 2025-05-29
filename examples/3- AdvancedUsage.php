<?php

require __DIR__ . '/../vendor/autoload.php';

use AhjDev\PhpTagMaker\TagMaker;
use AhjDev\PhpTagMaker\Node\HtmlTag;
use AhjDev\PhpTagMaker\Node\HtmlText; // For explicit text nodes if needed
use AhjDev\PhpTagMaker\HtmlClass;    // For standalone HtmlClass examples

// Initialize TagMaker, optionally with output formatting
$maker = new TagMaker();
$maker->formatOutput(true);

print("<h2>Advanced Tag Features</h2>");

// 1. Boolean Attributes and Data Attributes
print("<h3>1. Input with Boolean and Data Attributes:</h3>");
$input = HtmlTag::input('checkbox')
    ->setId('subscribe-checkbox')
    ->setDataAttribute('item-id', 'A123')
    ->setDataAttribute('item-type', 'newsletter')
    ->setAriaAttribute('label', 'Subscribe to newsletter')
    ->checked(true)  // Sets 'checked="checked"'
    ->disabled();     // Sets 'disabled="disabled"'
print($maker->run($input));
print("<br>");

$inputEnabled = HtmlTag::input('text')
    ->setId('username')
    ->disabled(false); // Attribute 'disabled' will not be present
print($maker->run($inputEnabled));
print("<hr>");


// 2. Appending and Prepending Children
print("<h3>2. List with Appended and Prepended Children:</h3>");
$list = HtmlTag::ul()->addClass('task-list');
$list->appendChild(HtmlTag::li('Second item, added first via appendChild'));
$list->prependChild(HtmlTag::li('First item, added second via prependChild'));
$list->appendChild(new HtmlText('A raw text node appended (not common for UL directly)'));
$list->appendChild(HtmlTag::li('Third item'));

print($maker->run($list));
print("<hr>");


// 3. Changing Tag Name with setName(), preserving children and attributes
print("<h3>3. Changing Tag Name (setName):</h3>");
$contentBlock = HtmlTag::div(
    'initial-class other-class', // Initial class(es) as string
    HtmlTag::p('This is a paragraph inside the original div.'),
    HtmlTag::span('This is a span, also a child.')
)->setId('content-block-1')->setDataAttribute('status', 'active');

print("<h4>Original div:</h4>");
print($maker->run($contentBlock));

$contentBlock->setName('article'); // Change from 'div' to 'article'
$contentBlock->setClass('article-class important'); // Replace all classes
$contentBlock->setDataAttribute('status', 'archived'); // Update data attribute
$contentBlock->appendChild(HtmlTag::footer('End of article.')); // Add new child

print("<h4>Changed to article (attributes and children should be preserved/updated):</h4>");
print($maker->run($contentBlock));
print("<hr>");


// 4. HtmlClass toggle method
print("<h3>4. HtmlClass toggle() method:</h3>");
$classManager = new HtmlClass('visible', 'active');
print("Initial classes: " . $classManager . "<br>"); // visible active

$classManager->toggle('active');
print("After toggling 'active': " . $classManager . "<br>"); // visible

$classManager->toggle('hidden');
print("After toggling 'hidden': " . $classManager . "<br>"); // visible hidden

$classManager->toggle('visible')->toggle('highlight');
print("After toggling 'visible' and 'highlight': " . $classManager . "<br>"); // hidden highlight
print("<hr>");

// 5. Using toggleClass on an HtmlTag element
print("<h3>5. HtmlTag toggleClass() method:</h3>");
$panel = HtmlTag::div('panel')->setId('info-panel');
print("Initial panel: ");
print($maker->run($panel));

$panel->toggleClass('visible', 'active');
print("Panel after toggling 'visible' and 'active': ");
print($maker->run($panel));

$panel->toggleClass('active'); // 'active' should be removed
print("Panel after toggling 'active' again: ");
print($maker->run($panel));
print("<hr>");

// 6. ARIA Attributes
print("<h3>6. ARIA Attributes Example:</h3>");
$button = HtmlTag::button('Click Me')
    ->setAriaAttribute('pressed', 'false')
    ->setAriaAttribute('label', 'Submit Form');
print($maker->run($button));
print("<br>");
$button->setAriaAttribute('pressed', 'true'); // Update ARIA attribute
$button->removeAriaAttribute('label');
$button->setAriaAttribute('describedby', 'tooltip-1');
print($maker->run($button));
print("<hr>");


print("<h2>End of Advanced Examples</h2>");