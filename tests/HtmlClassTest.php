<?php declare(strict_types=1);

namespace Tests;

use AhjDev\PhpTagMaker\HtmlClass;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the HtmlClass manager.
 *
 * @covers \AhjDev\PhpTagMaker\HtmlClass
 */
final class HtmlClassTest extends TestCase
{
    public function testCanBeCreatedWithInitialClasses(): void
    {
        $htmlClass = new HtmlClass('class1', 'class2');
        $this->assertSame('class1 class2', (string) $htmlClass);
    }

    public function testHandlesEmptyAndDuplicateClassesOnCreation(): void
    {
        $htmlClass = new HtmlClass('class1', ' ', 'class2', 'class1', '');
        $this->assertSame('class1 class2', (string) $htmlClass, 'Should ignore empty, whitespace, and duplicate classes.');
        $this->assertCount(2, $htmlClass);
    }

    public function testCanAddClass(): void
    {
        $htmlClass = new HtmlClass('class1');
        $htmlClass->add('class2');
        $this->assertSame('class1 class2', (string) $htmlClass);
    }

    public function testAddingExistingClassDoesNothing(): void
    {
        $htmlClass = new HtmlClass('class1');
        $htmlClass->add('class1');
        $this->assertCount(1, $htmlClass);
        $this->assertSame('class1', (string) $htmlClass);
    }

    public function testCanRemoveClass(): void
    {
        $htmlClass = new HtmlClass('class1', 'class2', 'class3');
        $htmlClass->remove('class2');
        $this->assertSame('class1 class3', (string) $htmlClass);
        $this->assertFalse($htmlClass->has('class2'));
    }

    public function testHasChecksForClassExistence(): void
    {
        $htmlClass = new HtmlClass('class1', 'class2');
        $this->assertTrue($htmlClass->has('class1'));
        $this->assertFalse($htmlClass->has('class3'));
    }

    public function testToggleAddsAndRemovesClasses(): void
    {
        $htmlClass = new HtmlClass('active');

        // Toggle to remove
        $htmlClass->toggle('active');
        $this->assertFalse($htmlClass->has('active'), 'Toggle should remove an existing class.');
        $this->assertSame('', (string) $htmlClass);

        // Toggle to add
        $htmlClass->toggle('active');
        $this->assertTrue($htmlClass->has('active'), 'Toggle should add a non-existing class.');
        $this->assertSame('active', (string) $htmlClass);

        // Toggle a new one
        $htmlClass->toggle('visible');
        $this->assertSame('active visible', (string) $htmlClass);
    }

    public function testMergeWithAnotherHtmlClassInstance(): void
    {
        $htmlClass1 = new HtmlClass('class1', 'class2');
        $htmlClass2 = new HtmlClass('class2', 'class3');

        $htmlClass1->merge($htmlClass2);

        $this->assertSame('class1 class2 class3', (string) $htmlClass1, 'Should merge classes from another instance without duplicates.');
    }

    public function testMergeWithString(): void
    {
        $htmlClass = new HtmlClass('class1');
        $htmlClass->merge('class2 class3 class2'); // Add duplicate to test filtering

        $this->assertSame('class1 class2 class3', (string) $htmlClass, 'Should merge classes from a string without duplicates.');
    }

    public function testCanBeIterated(): void
    {
        $classes = ['class1', 'class2', 'class3'];
        $htmlClass = new HtmlClass(...$classes);

        $iteratedClasses = [];
        foreach ($htmlClass as $class) {
            $iteratedClasses[] = $class;
        }

        $this->assertEquals($classes, $iteratedClasses);
    }

    public function testAsArrayReturnsCorrectArray(): void
    {
        $classes = ['class1', 'class2'];
        $htmlClass = new HtmlClass(...$classes);
        $this->assertEquals($classes, $htmlClass->asArray());
    }
}
