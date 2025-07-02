# PhpTagMaker Library

**PhpTagMaker** is a fluent and powerful PHP library for programmatically building HTML strings. It leverages `DOMDocument` behind the scenes, ensuring well-formed, valid, and secure output. This enhanced version includes advanced features for attribute management, child manipulation, and overall flexibility.

## Table of Contents

- [PhpTagMaker Library](#phptagmaker-library)
  - [Table of Contents](#table-of-contents)
  - [Key Features](#key-features)
  - [Requirements](#requirements)
  - [Installation](#installation)
  - [Quick Start](#quick-start)
  - [Security](#security)
  - [Core Concepts](#core-concepts)
    - [`TagMaker`](#tagmaker)
    - [`HtmlTag`](#htmltag)
    - [Node Types (`Node`)](#node-types-node)
    - [`HtmlClass`](#htmlclass)
  - [API Documentation \& Advanced Usage](#api-documentation--advanced-usage)
    - [Creating Tags](#creating-tags)
    - [Managing Children](#managing-children)
    - [Managing Attributes](#managing-attributes)
      - [Generic Attributes](#generic-attributes)
      - [The `id` Attribute](#the-id-attribute)
      - [CSS Classes](#css-classes)
      - [Boolean Attributes](#boolean-attributes)
      - [`data-*` Attributes](#data--attributes)
      - [`aria-*` Attributes](#aria--attributes)
    - [Changing the Tag Name](#changing-the-tag-name)
    - [Output Formatting](#output-formatting)
  - [Examples](#examples)
  - [Contributing Guide](#contributing-guide)
  - [License](#license)

## Key Features

* **Fluent Interface**: Build complex HTML structures in a readable, chainable way.
* **DOM-Powered**: Uses `DOMDocument` to generate standard and valid HTML.
* **Full Tag Support**: Includes static helper methods for most standard HTML5 tags.
* **Advanced Attribute Control**: Full management of generic, `id`, `class`, Boolean, `data-*`, and `aria-*` attributes.
* **Flexible Child Management**: Add children at creation time or with `appendChild` and `prependChild` methods.
* **Smart Error Handling**: Prevents adding children to void elements (like `<img>`).
* **Built-in Security**: Prevents XSS attacks by automatically escaping text content.
* **Output Formatting**: Option for readable, indented HTML output for easier debugging.
* **Modern Coding**: Uses strict types and modern PHP features.

## Requirements

* PHP 8.0 or higher
* `ext-dom` extension

## Installation

You can install the library via [Composer](https://getcomposer.org/):

```bash
composer require ahjdev/phptagmaker
````

## Quick Start

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use AhjDev\PhpTagMaker\TagMaker;
use AhjDev\PhpTagMaker\Node\HtmlTag;

// Build a simple HTML structure
$output = TagMaker::build(
    HtmlTag::div(
        'container main-content', // CSS classes
        HtmlTag::h1('Welcome to PhpTagMaker!'),
        HtmlTag::p(
            'This is a simple paragraph with a ',
            HtmlTag::a('[https://example.com](https://example.com)', 'link')->setId('my-link')
        )->addClass('content')
    ),
    true // Enable output formatting
);

echo $output;
```


Expected Output:

```html
<div class="container main-content">
  <h1>Welcome to PhpTagMaker!</h1>
  <p class="content">This is a simple paragraph with a <a href="[https://example.com](https://example.com)" id="my-link">link</a></p>
</div>
```

## Security

This library helps mitigate Cross-Site Scripting (XSS) vulnerabilities by default.

  * **Automatic Escaping**: By using `DOMDocument`, all text content added via `HtmlText` nodes or plain strings is automatically escaped (e.g., `<` becomes `&lt;`).
  * **CDATA Sections**: For content that should not be parsed by the HTML parser (like inline scripts), you can use the `EscapedText` node, which wraps the content in `<![CDATA[...]]>`.

**Your Responsibility**: Despite the built-in security, you must still be cautious. Never pass untrusted user input directly into attributes that can execute code (like `href` with `javascript:` values or `onclick` events). Always validate and sanitize user input before using it in such attributes.

## Core Concepts

### `TagMaker`

This is the main engine of the library that transforms the node structure into the final HTML string.

  * `TagMaker::build(Node $node, bool $format = false)`: A static method for quickly building HTML.
  * `$maker->run(Node $node)`: Processes the node and generates the output.
  * `$maker->formatOutput(true)`: Enables output formatting.

### `HtmlTag`

This class represents an HTML tag and is the most frequently used node in the library.

  * `HtmlTag::div(...)`, `HtmlTag::p(...)`, etc.: Static helper methods for quickly creating tags.
  * `HtmlTag::make('tag', ...)`: Another way to create a tag.

### Node Types (`Node`)

All elements inherit from the `Node` class.

  * **`HtmlTag`**: A standard HTML tag.
  * **`HtmlText`**: A simple text node whose special characters are automatically escaped.
  * **`EscapedText`**: A CDATA section whose content is not processed by the parser.
  * **`HtmlTagMulti`**: A tool for quickly creating deeply nested structures.

### `HtmlClass`

A powerful helper class for managing the CSS classes of a tag. It provides methods for adding (`add`), removing (`remove`), toggling (`toggle`), and merging (`merge`) classes, and prevents duplicates.

## API Documentation & Advanced Usage

### Creating Tags

**1. Using static helper methods (recommended method):**

```php
use AhjDev\PhpTagMaker\Node\HtmlTag;

$div = HtmlTag::div('container', 'Div content');
$link = HtmlTag::a('[https://example.com](https://example.com)', 'Click here');
$image = HtmlTag::img('/image.jpg', 'Alternative text');
```

**2. Using `make` or the main constructor:**

```php
$customTag = HtmlTag::make('my-custom-tag', 'Content');
$paragraph = new HtmlTag('p', 'A new paragraph.');
```

### Managing Children

**1. Adding children at creation time:**

```php
$article = HtmlTag::article(
    HtmlTag::h1('Article Title'),
    HtmlTag::p('First paragraph.'),
    'This is a simple text as a child.'
);
```

**2. Adding children after creation:**

```php
$list = HtmlTag::ul();
$list->appendChild(HtmlTag::li('Item 2'));
$list->prependChild(HtmlTag::li('Item 1')); // Adds to the beginning of the list
```

### Managing Attributes

#### Generic Attributes

```php
$tag = HtmlTag::div()
    ->setAttribute('title', 'My Title')
    ->setAttribute('lang', 'en');

echo $tag->getAttribute('title'); // "My Title"
var_dump($tag->hasAttribute('lang')); // true
$tag->removeAttribute('lang');
```

#### The `id` Attribute

```php
$section = HtmlTag::section()->setId('main-content');
echo $section->getId(); // "main-content"
```

#### CSS Classes

```php
$button = HtmlTag::button('Submit');

// Replace all classes
$button->setClass('btn', 'btn-primary');

// Add a new class
$button->addClass('btn-large'); // "btn btn-primary btn-large"

// Remove a class
$button->removeClass('btn-large'); // "btn btn-primary"

// Toggle a class
$button->toggleClass('active'); // The 'active' class is added
$button->toggleClass('active'); // The 'active' class is removed
```

#### Boolean Attributes

These attributes are added to the tag if `true` and removed if `false`.

```php
$input = HtmlTag::input('checkbox')
    ->checked()      // Adds checked="checked"
    ->disabled();     // Adds disabled="disabled"

// To remove
$input->disabled(false); // The 'disabled' attribute is removed
```

#### `data-*` Attributes

```php
$item = HtmlTag::li('My Item')
    ->setDataAttribute('item-id', '123')
    ->setDataAttribute('item-type', 'product');

echo $item->getDataAttribute('item-id'); // "123"
```

#### `aria-*` Attributes

To improve accessibility:

```php
$alert = HtmlTag::div()
    ->setAriaAttribute('role', 'alert')
    ->setAriaAttribute('live', 'assertive');
```

### Changing the Tag Name

You can change a tag's name after it has been created. Attributes and children are preserved.

```php
$element = HtmlTag::div(null, 'Content')->setClass('box');
$element->setName('section'); // The tag changes from <div> to <section>
```

### Output Formatting

To make the HTML output more readable in a development environment, you can enable formatting.

```php
$maker = new TagMaker();
$maker->formatOutput(true);
$html = $maker->run(
    HtmlTag::ul(HtmlTag::li('Item 1'), HtmlTag::li('Item 2'))
);
// The output will be displayed with indentation.
```

## Examples

For more practical scenarios, Please see the `examples/` directory for more usage scenarios:

  * `examples/1-SimpleMaker.php`: Basic usage.
  * `examples/2-FormatOutput.php`: Demonstrates output formatting and various node types.
  * `examples/3-AdvancedUsage.php`: Showcases enhanced attribute handling, child management, and `setName`.

## Contributing Guide

Submissions intended to enhance this software are permissible under the condition that they conform to established project protocols. All proposed modifications shall be tendered via Pull Requests for subsequent formal review. The reporting of software anomalies or functional deficiencies is to be registered within the designated "Issues" section of the repository.

For the purposes of local development and validation, a set of Composer scripts is provided. Adherence to these scripts is requisite for the maintenance of code quality and stylistic uniformity.

  * **`composer test`**: Executes the PHPUnit test suite to validate the functionality of the codebase.
  * **`composer cs`**: Initiates a check for conformance with the established coding style standards.
  * **`composer cs-fix`**: Engages a process to automatically rectify any deviations from the established coding style.
  * **`composer analyse`**: Commences a static analysis of the source code, utilizing the PHPStan tool, for the purpose of identifying potential defects and logical inconsistencies prior to runtime execution.

## License

This library is released under the **GPL-3.0-only** License. See the [LICENSE](https://www.google.com/search?q=LICENSE) file for more details.
