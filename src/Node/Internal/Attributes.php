<?php declare(strict_types=1);

namespace AhjDev\PhpTagMaker\Node\Internal;

use AhjDev\PhpTagMaker\HtmlClass;
use DOMAttr;
use Iterator;
use ArrayIterator;

/**
 * @internal
 * This trait relies on the using class to have:
 * - public HtmlClass $class
 * - private array $attributes
 */
trait Attributes
{
    public function setClass(string ...$classes): self
    {
        $this->class = new HtmlClass(...$classes);
        return $this;
    }

    public function getClass(): null|string|array
    {
        $classString = $this->getAttribute('class');
        if ($classString) {
            $parts = explode(' ', $classString);
            return count($parts) === 1 ? $parts[0] : $parts;
        }
        return null;
    }

    public function addClass(string ...$classes): self
    {
        foreach ($classes as $class) {
            $this->class->add($class);
        }
        return $this;
    }

    public function removeClass(string ...$classes): self
    {
        foreach ($classes as $class) {
            $this->class->remove($class);
        }
        return $this;
    }

    public function toggleClass(string ...$classes): self
    {
        foreach ($classes as $class) {
            $this->class->toggle($class);
        }
        return $this;
    }

    public function setId(string $id): self
    {
        return $this->setAttribute('id', $id);
    }

    public function getId(): ?string
    {
        return $this->getAttribute('id');
    }

    public function setAttribute(string $qualifiedName, string $value): self
    {
        $this->attributes[$qualifiedName] = $value;
        return $this;
    }

    public function removeAttribute(string $qualifiedName): self
    {
        unset($this->attributes[$qualifiedName]);
        return $this;
    }

    public function hasAttribute(string $qualifiedName): bool
    {
        return array_key_exists($qualifiedName, $this->attributes);
    }

    public function getAttribute(string $qualifiedName): ?string
    {
        return $this->attributes[$qualifiedName] ?? null;
    }

    public function setBooleanAttribute(string $qualifiedName, bool $value = true): self
    {
        if ($value) {
            $this->setAttribute($qualifiedName, $qualifiedName);
        } else {
            $this->removeAttribute($qualifiedName);
        }
        return $this;
    }

    public function disabled(bool $isDisabled = true): self
    {
        return $this->setBooleanAttribute('disabled', $isDisabled);
    }

    public function checked(bool $isChecked = true): self
    {
        return $this->setBooleanAttribute('checked', $isChecked);
    }

    public function setDataAttribute(string $key, string $value): self
    {
        return $this->setAttribute('data-' . $key, $value);
    }

    public function getDataAttribute(string $key): ?string
    {
        return $this->getAttribute('data-' . $key);
    }

    public function setAriaAttribute(string $key, string $value): self
    {
        return $this->setAttribute('aria-' . $key, $value);
    }

    public function getAriaAttribute(string $key): ?string
    {
        return $this->getAttribute('aria-' . $key);
    }

    public function iterAttributes(): Iterator
    {
        return new ArrayIterator($this->attributes);
    }
}