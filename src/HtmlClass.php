<?php declare(strict_types=1);

namespace AhjDev\PhpTagMaker;

use Countable;
use Stringable;
use IteratorAggregate;
use Traversable; // For Generator type hint

final class HtmlClass implements Stringable, IteratorAggregate, Countable
{
    private array $classList = [];

    public function __construct(string ...$classes)
    {
        // Trim and filter empty classes, then ensure uniqueness
        $trimmedClasses = array_map(static fn ($e) => trim($e), $classes);
        $this->classList = array_values(array_unique(array_filter($trimmedClasses)));
    }

    public function __toString(): string
    {
        return implode(' ', $this->classList);
    }

    public function has(string $class): bool
    {
        return in_array(trim($class), $this->classList, true);
    }

    public function add(string $class): self
    {
        $class = trim($class);
        // Add only if it's not empty and not already present
        if (!(empty($class) || $this->has($class))) {
            $this->classList[] = $class;
        }
        return $this;
    }

    public function remove(string $class): self
    {
        $class = trim($class);
        if (($pos = array_search($class, $this->classList, true)) !== false) {
            unset($this->classList[$pos]);
            $this->classList = array_values($this->classList); // Re-index array
        }
        return $this;
    }

    /**
     * Toggles a class: adds it if not present, removes it if present.
     * @param string $class The class name to toggle.
     */
    public function toggle(string $class): self
    {
        $class = trim($class);
        if (empty($class)) {
            return $this;
        }

        if ($this->has($class)) {
            $this->remove($class);
        } else {
            $this->add($class);
        }
        return $this;
    }

    public function merge(string|self ...$classes): self
    {
        $newClasses = [];
        foreach ($classes as $classInput) {
            if ($classInput instanceof self) {
                $newClasses = array_merge($newClasses, $classInput->asArray());
            } elseif (is_string($classInput)) {
                // Split string by space in case multiple classes are passed in one string
                $parts = array_map('trim', explode(' ', $classInput));
                $newClasses = array_merge($newClasses, array_filter($parts));
            }
        }

        foreach($newClasses as $nc) {
            $this->add($nc); // Use add to ensure uniqueness and trimming
        }
        return $this;
    }

    public function asArray(): array
    {
        return $this->classList;
    }

    public function count(): int
    {
        return count($this->classList);
    }

    /**
     * @return Traversable<int, string>
     */
    public function getIterator(): Traversable // Changed from \Generator to Traversable for broader compatibility
    {
        yield from $this->classList;
    }
}