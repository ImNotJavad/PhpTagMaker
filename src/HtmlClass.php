<?php declare(strict_types=1);

namespace AhjDev\PhpTagMaker;

use Countable;
use IteratorAggregate;
use Stringable;
use Traversable;

/**
 * A robust and fluent manager for an element's CSS classes.
 *
 * @implements IteratorAggregate<int, string>
 */
final class HtmlClass implements Stringable, IteratorAggregate, Countable
{
    /**
     * @var list<string> An array holding the unique list of class names. A `list` is a more specific type of array with sequential integer keys starting from 0.
     */
    private array $classList = [];

    /**
     * HtmlClass constructor.
     *
     * @param string ...$classes Initial classes to add.
     */
    public function __construct(string ...$classes)
    {
        $this->merge(...$classes);
    }

    /**
     * Returns the space-separated string of classes.
     *
     */
    public function __toString(): string
    {
        return \implode(' ', $this->classList);
    }

    /**
     * Checks if a specific class exists.
     *
     * @param string $class The class name to check.
     */
    public function has(string $class): bool
    {
        return \in_array(\trim($class), $this->classList, true);
    }

    /**
     * Adds a class if it does not already exist.
     *
     * @param string $class The class name to add.
     */
    public function add(string $class): self
    {
        $class = \trim($class);
        if ($class !== '' && !$this->has($class)) {
            $this->classList[] = $class;
        }
        return $this;
    }

    /**
     * Removes a class if it exists.
     *
     * @param string $class The class name to remove.
     */
    public function remove(string $class): self
    {
        $class = \trim($class);
        $pos = \array_search($class, $this->classList, true);
        if ($pos !== false) {
            unset($this->classList[$pos]);
            // Re-index the array to maintain the `list` type.
            $this->classList = \array_values($this->classList);
        }
        return $this;
    }

    /**
     * Toggles a class.
     *
     * @param string $class The class name to toggle.
     */
    public function toggle(string $class): self
    {
        $class = \trim($class);
        if ($class === '') {
            return $this;
        }

        if ($this->has($class)) {
            $this->remove($class);
        } else {
            $this->add($class);
        }
        return $this;
    }

    /**
     * Merges classes from strings or other HtmlClass instances.
     *
     * @param string|self ...$classes A mix of strings or HtmlClass instances.
     */
    public function merge(string|self ...$classes): self
    {
        foreach ($classes as $classInput) {
            $newClasses = [];
            if ($classInput instanceof self) {
                $newClasses = $classInput->asArray();
            } elseif (\is_string($classInput)) {
                $newClasses = \explode(' ', $classInput);
            }

            foreach ($newClasses as $nc) {
                $this->add($nc);
            }
        }
        return $this;
    }

    /**
     * Returns the list of classes as an array.
     *
     * @return list<string>
     */
    public function asArray(): array
    {
        return $this->classList;
    }

    /**
     * Returns the number of classes.
     *
     */
    public function count(): int
    {
        return \count($this->classList);
    }

    /**
     * Returns an iterator for the class list.
     *
     * @return Traversable<int, string>
     */
    public function getIterator(): Traversable
    {
        yield from $this->classList;
    }
}
