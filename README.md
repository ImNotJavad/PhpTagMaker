# PhpTagMaker (Enhanced Version)

**PhpTagMaker** is a fluent and powerful PHP library for programmatically building HTML strings. It leverages `DOMDocument` behind the scenes, ensuring well-formed and valid HTML output. This enhanced version includes significant improvements for advanced attribute management, child manipulation, and overall flexibility.

[![License: GPL-3.0-only](https://img.shields.io/badge/License-GPL--3.0--only-blue.svg)](https://www.gnu.org/licenses/gpl-3.0.html)
[![PHP Version Support](https://img.shields.io/packagist/php/ahjdev/phptagmaker)](https://packagist.org/packages/ahjdev/phptagmaker) ## Table of Contents

- [PhpTagMaker (Enhanced Version)](#phptagmaker-enhanced-version)
  - [Key Features](#key-features)
  - [Installation](#installation)
  - [Quick Start](#quick-start)
  - [Core Concepts](#core-concepts)
    - [TagMaker](#tagmaker)
    - [HtmlTag](#htmltag)
    - [Node Types](#node-types)
    - [HtmlClass](#htmlclass)
  - [Advanced Usage](#advanced-usage)
    - [Creating Tags](#creating-tags)
      - [Static Helper Methods](#static-helper-methods)
      - [HtmlTag Constructor](#htmltag-constructor)
    - [Managing Children](#managing-children)
      - [Adding at Construction](#adding-at-construction)
      - [`appendChild()` and `prependChild()`](#appendchild-and-prependchild)
    - [Attribute Management](#attribute-management)
      - [Generic Attributes (`setAttribute`, `getAttribute`, `hasAttribute`, `removeAttribute`)](#generic-attributes-setattribute-getattribute-hasattribute-removeattribute)
      - [ID (`setId`, `getId`)](#id-setid-getid)
      - [CSS Classes (`setClass`, `addClass`, `removeClass`, `toggleClass`, Class Instance Management)](#css-classes-setclass-addclass-removeclass-toggleclass-class-instance-management)
      - [Boolean Attributes (`setBooleanAttribute`, `disabled`, `checked`)](#boolean-attributes-setbooleanattribute-disabled-checked)
      - [Data Attributes (`setDataAttribute`, `getDataAttribute`, `removeDataAttribute`, `hasDataAttribute`)](#data-attributes-setdataattribute-getdataattribute-removedataattribute-hasdataattribute)
      - [ARIA Attributes (`setAriaAttribute`, `getAriaAttribute`, `removeAriaAttribute`, `hasAriaAttribute`)](#aria-attributes-setariaattribute-getariaattribute-removeariaattribute-hasariaattribute)
      - [Iterating Attributes (`iterAttributes`)](#iterating-attributes-iterattributes)
    - [Changing Tag Name (`setName`)](#changing-tag-name-setname)
    - [Output Formatting](#output-formatting)
    - [Specialized Nodes](#specialized-nodes)
      - [`HtmlText` (Unscaped Text)](#htmltext-unscaped-text)
      - [`EscapedText` (CDATA)](#escapedtext-cdata)
      - [`HtmlTagMulti` (Nested Tags)](#htmltagmulti-nested-tags)
  - [Examples](#examples)
  - [Contributing](#contributing)
  - [License](#license)

## Key Features

* **Fluent Interface**: Chain methods to build complex HTML structures intuitively.
* **DOM-Powered**: Uses `DOMDocument` internally for robust and well-formed HTML generation.
* **Comprehensive Tag Support**: Includes static helper methods for most standard HTML5 tags.
* **Advanced Attribute Control**:
    * Generic, ID, Class, Boolean, Data, and ARIA attributes.
    * Powerful `HtmlClass` object for managing CSS classes.
* **Flexible Child Management**: Add children during construction, or append/prepend them later.
* **Text Node Handling**: Supports unescaped text (`HtmlText`) and CDATA sections for escaped content (`EscapedText`).
* **Output Formatting**: Option to nicely format the HTML output with indentation.
* **Extensible**: Based on an abstract `Node` class, allowing for custom node types if needed.
* **Modern PHP**: Uses strict types and modern PHP features.

## Installation

You can install PhpTagMaker via [Composer](https://getcomposer.org/):

```bash
composer require ahjdev/phptagmaker
````

*(Note: Replace `ahjdev/phptagmaker` with your actual package name if you fork and publish it under a different name.)*

## Quick Start

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use AhjDev\PhpTagMaker\TagMaker;
use AhjDev\PhpTagMaker\Node\HtmlTag;
use AhjDev\PhpTagMaker\HtmlClass; //

// Simple build
$output = TagMaker::build(
    HtmlTag::div( //
        'my-class-name another-class', //
        HtmlTag::h1('Hello, PhpTagMaker!'),
        HtmlTag::p(
            'This is a paragraph with a ',
            HtmlTag::a('[https://example.com](https://example.com)', 'link')->setId('my-link')->setDataAttribute('target', 'new-window')
        )->addClass('content')
    ),
    true // Format output
);

echo $output;
```

Expected Output:

```html
<div>
  <h1 class="my-class-name another-class">Hello, PhpTagMaker!</h1>
  <p class="content">This is a paragraph with a <a href="[https://example.com](https://example.com)" id="my-link" data-target="new-window">link</a></p>
</div>
```

*(Output structure might vary slightly based on exact implementation of class handling on the parent div in the example)*

## Core Concepts

### TagMaker

The `TagMaker` class is the main entry point for generating the final HTML string.

  * `new TagMaker()`: Creates an instance.
  * `formatOutput(bool $option = true)`: Enables or disables formatted HTML output.
  * `run(Node $node)`: Processes the given `Node` (usually an `HtmlTag`) and returns the HTML string.
  * `TagMaker::build(Node $node, bool $format = false)`: A static helper to quickly create a `TagMaker` instance, configure formatting, and run it.

### HtmlTag

`HtmlTag` represents an HTML element. It's the most commonly used node type.

  * `HtmlTag::make(string $tag, Node|string ...$value)`: Static factory method.
  * `new HtmlTag(string $tag, Node|string ...$value)`: Constructor.
  * It uses the `Attributes` trait for attribute manipulation and `DefaultTags` trait for static helpers (e.g., `HtmlTag::div()`).

### Node Types

All elements generated by PhpTagMaker extend the abstract `Node` class.
Each `Node` must implement the `toDomNode()` method, which converts it into a `\DOMNode` object.

  * `HtmlTag`: Represents a standard HTML element.
  * `HtmlText`: Represents a plain text node (special HTML characters will be escaped by `DOMDocument` on output).
  * `EscapedText`: Represents a CDATA section, useful for embedding content that should not be parsed (e.g., inline scripts or styles, though dedicated tags are better).
  * `HtmlTagMulti`: A utility to create a deeply nested structure of tags with a single content.

### HtmlClass

The `HtmlClass` class provides a convenient way to manage an element's CSS classes.

  * `new HtmlClass(string ...$classes)`: Constructor.
  * `add(string $class)`: Adds a class if not present.
  * `remove(string $class)`: Removes a class if present.
  * `toggle(string $class)`: Adds a class if absent, removes it if present.
  * `has(string $class)`: Checks if a class exists.
  * `merge(string|self ...$classes)`: Merges classes from strings or other `HtmlClass` instances.
  * `__toString()`: Returns the space-separated string of classes.
  * Implements `Countable` and `IteratorAggregate`.

## Advanced Usage

### Creating Tags

#### Static Helper Methods

The `HtmlTag` class (via the `DefaultTags` trait) provides static factory methods for all common HTML tags. This is often the most convenient way to create tags.

```php
use AhjDev\PhpTagMaker\Node\HtmlTag;

$div = HtmlTag::div('This is a div.');
$link = HtmlTag::a('[https://example.com](https://example.com)', 'Click here');
$image = HtmlTag::img('/path/to/image.jpg', null, null, 'Alternative text'); // src, height, width, alt
$input = HtmlTag::input('text')->setAttribute('placeholder', 'Enter text...'); //
```

The first argument to tag methods that accept content can be a string, another `Node` object, or an `HtmlClass` instance (specifically for `div` and some others).

#### HtmlTag Constructor

You can also use `HtmlTag::make()` or `new HtmlTag()` directly.

```php
use AhjDev\PhpTagMaker\Node\HtmlTag;

$customTag = HtmlTag::make('my-custom-tag', 'Content');
$paragraph = new HtmlTag('p', 'This is a paragraph node.');
```

### Managing Children

#### Adding at Construction

Pass child nodes or strings as subsequent arguments to the constructor or static factory methods:

```php
$article = HtmlTag::article(
    HtmlTag::h1('Article Title'),
    HtmlTag::p('First paragraph.'),
    'This is a simple string child.',
    HtmlTag::p('Another paragraph.')
);
```

#### `appendChild()` and `prependChild()`

You can add children to an `HtmlTag` after its creation:

```php
$list = HtmlTag::ul();
$list->appendChild(HtmlTag::li('Item 2'));
$list->prependChild(HtmlTag::li('Item 1')); // Prepends
$list->appendChild('Just text, will be wrapped in HtmlText');

// $list will render: <ul><li>Item 1</li><li>Item 2</li>Just text, will be wrapped in HtmlText</ul>
```

### Attribute Management

The `Attributes` trait provides a rich API for managing HTML attributes on `HtmlTag` instances.

#### Generic Attributes (`setAttribute`, `getAttribute`, `hasAttribute`, `removeAttribute`)

```php
$tag = HtmlTag::div()->setAttribute('data-custom', 'value123');
$tag->setAttribute('title', 'My Tooltip');

echo $tag->getAttribute('data-custom'); // value123
var_dump($tag->hasAttribute('title')); // true

$tag->removeAttribute('data-custom');
var_dump($tag->hasAttribute('data-custom')); // false
```

#### ID (`setId`, `getId`)

```php
$section = HtmlTag::section()->setId('main-content');
echo $section->getId(); // main-content
```

#### CSS Classes (`setClass`, `addClass`, `removeClass`, `toggleClass`, Class Instance Management)

`HtmlTag` internally uses an `HtmlClass` instance to manage its classes.

```php
use AhjDev\PhpTagMaker\HtmlClass;

$button = HtmlTag::button('Submit');

// Set initial classes (replaces any existing)
$button->setClass('btn', 'btn-primary'); // Becomes "btn btn-primary"

// Add more classes
$button->addClass('btn-large', 'active'); // Becomes "btn btn-primary btn-large active"

// Remove a class
$button->removeClass('btn-large'); // Becomes "btn btn-primary active"

// Toggle classes
$button->toggleClass('active'); // 'active' removed -> "btn btn-primary"
$button->toggleClass('active', 'focus'); // 'active' added, 'focus' added -> "btn btn-primary active focus"

// Get class string or array
var_dump($button->getClass()); // ['btn', 'btn-primary', 'active', 'focus'] (or string if only one)

// Direct manipulation of the HtmlClass object (if needed, and made accessible)
// $button->class is the HtmlClass instance in the enhanced version
$button->class->add('another-via-instance');
```

When creating tags like `div` using the static helper, you can pass an `HtmlClass` instance or a string for classes:

```php
$div1 = HtmlTag::div(new HtmlClass('class1', 'class2'), 'Content'); //
$div2 = HtmlTag::div('class3 class4', 'More content'); //
```

#### Boolean Attributes (`setBooleanAttribute`, `disabled`, `checked`)

Boolean attributes are present if true, absent if false.

```php
$input = HtmlTag::input('checkbox')
    ->setBooleanAttribute('checked', true) // Or simply ->checked()
    ->disabled(); // Or ->disabled(true)

// To remove:
$input->disabled(false); // 'disabled' attribute is removed
```

#### Data Attributes (`setDataAttribute`, `getDataAttribute`, `removeDataAttribute`, `hasDataAttribute`)

Manage `data-*` attributes easily.

```php
$item = HtmlTag::li('My Item')
    ->setDataAttribute('item-id', '123')
    ->setDataAttribute('item-type', 'product');

echo $item->getDataAttribute('item-id'); // 123
$item->removeDataAttribute('item-type');
```

#### ARIA Attributes (`setAriaAttribute`, `getAriaAttribute`, `removeAriaAttribute`, `hasAriaAttribute`)

Manage `aria-*` attributes for accessibility.

```php
$alert = HtmlTag::div()
    ->setAriaAttribute('role', 'alert')
    ->setAriaAttribute('live', 'assertive');

echo $alert->getAriaAttribute('role'); // alert
```

#### Iterating Attributes (`iterAttributes`)

You can iterate over all attributes of an element. Each attribute is a `DOMAttr` object.

```php
$tagWithAttrs = HtmlTag::div()
    ->setId('myDiv')
    ->setClass('container')
    ->setDataAttribute('info', 'some-data');

foreach ($tagWithAttrs->iterAttributes() as $attr) {
    // $attr is an instance of \DOMAttr
    echo $attr->nodeName . ': ' . $attr->nodeValue . "\n";
}
// Output might be:
// id: myDiv
// class: container
// data-info: some-data
```

### Changing Tag Name (`setName`)

You can change the tag name of an existing `HtmlTag` instance. Attributes and children are preserved.

```php
$element = HtmlTag::div(HtmlTag::p('Content inside.'))->setClass('box');
echo TagMaker::build($element); // <div class="box"><p>Content inside.</p></div>

$element->setName('section'); // Change to <section>
$element->addClass('important');
echo TagMaker::build($element); // <section class="box important"><p>Content inside.</p></section>
```

### Output Formatting

The `TagMaker` can format the HTML output with indentation and newlines for better readability.

```php
$maker = new TagMaker();
$maker->formatOutput(true); // Enable formatting

$html = $maker->run(
    HtmlTag::ul(
        HtmlTag::li('Item 1'),
        HtmlTag::li('Item 2')
    )
);
// $html will be nicely formatted.
```

### Specialized Nodes

#### `HtmlText` (Unscaped Text)

For adding plain text content. `DOMDocument` will handle necessary escaping of special HTML characters (e.g., `<`, `>`, `&`) during rendering.

```php
use AhjDev\PhpTagMaker\Node\HtmlText;

$textNode = HtmlText::make('This text might contain < & > characters.');
$div = HtmlTag::div($textNode);
// Output: <div>This text might contain &lt; &amp; &gt; characters.</div>
```

#### `EscapedText` (CDATA)

For content that should explicitly not be parsed by the HTML parser, wrapped in `<![CDATA[...]]>`.

```php
use AhjDev\PhpTagMaker\Node\EscapedText;

$scriptContent = EscapedText::make('if (a < b && b > c) { console.log("CDATA"); }');
$scriptTag = HtmlTag::script($scriptContent);
// Output: <script><![CDATA[if (a < b && b > c) { console.log("CDATA"); }]]></script>
```

#### `HtmlTagMulti` (Nested Tags)

Creates a sequence of nested tags with the same content at the deepest level.

```php
use AhjDev\PhpTagMaker\Node\HtmlTagMulti;

$nested = HtmlTagMulti::make(['div', 'p', 'strong'], 'Deeply nested text');
// Output: <div><p><strong>Deeply nested text</strong></p></div>

$nestedWithNode = HtmlTagMulti::make(
    ['section', 'article'],
    HtmlTag::h1('Title'), 'Followed by text.'
);
// Output: <section><article><h1>Title</h1>Followed by text.</article></section>
```

## Examples

Please see the `examples/` directory for more usage scenarios:

  * `examples/1-SimpleMaker.php`: Basic usage.
  * `examples/2-FormatOutput.php`: Demonstrates output formatting and various node types.
  * `examples/3-AdvancedUsage.php`: Showcases enhanced attribute handling, child management, and `setName`.

## Contributing

Contributions are welcome\! Please feel free to submit pull requests or open issues.
If you plan to contribute, please ensure your code adheres to the existing coding style (you can use PHP CS Fixer with the provided configuration `/.php-cs-fixer.dist.php`).

Development scripts (from `composer.json`):

  * `composer cs`: Check code style.
  * `composer cs-fix`: Fix code style.
  * `composer build`: Alias for `cs-fix`.

(Consider adding guidelines for running tests if you implement a test suite.)

## License

PhpTagMaker is licensed under the GPL-3.0-only License. See the [LICENSE](https://www.google.com/search?q=LICENSE) file for details (you would need to add a https://www.google.com/search?q=LICENSE file with the GPL-3.0 text).
