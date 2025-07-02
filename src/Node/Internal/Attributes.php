<?php declare(strict_types=1);

namespace AhjDev\PhpTagMaker\Node\Internal;

use AhjDev\PhpTagMaker\HtmlClass;
use ArrayIterator;
use Iterator;

/**
 * @internal
 * A trait that provides a rich API for managing HTML attributes on an HtmlTag.
 */
trait Attributes
{
    /**
     * @var HtmlClass Manages the CSS classes of the element.
     */
    private HtmlClass $class;

    /**
     * @var array<string, string> Holds all standard attributes of the element.
     */
    private array $attributes;

    /**
     * Replaces all existing CSS classes with a new set.
     *
     * @param string ...$classes The new classes to set.
     */
    public function setClass(string ...$classes): self
    {
        $this->class = new HtmlClass(...$classes);
        return $this;
    }

    /**
     * Adds one or more CSS classes.
     *
     * @param string ...$classes The classes to add.
     */
    public function addClass(string ...$classes): self
    {
        $this->class->merge(...$classes);
        return $this;
    }

    /**
     * Removes one or more CSS classes.
     *
     * @param string ...$classes The classes to remove.
     */
    public function removeClass(string ...$classes): self
    {
        foreach ($classes as $class) {
            $this->class->remove($class);
        }
        return $this;
    }

    /**
     * Toggles one or more CSS classes.
     *
     * @param string ...$classes The classes to toggle.
     */
    public function toggleClass(string ...$classes): self
    {
        foreach ($classes as $class) {
            $this->class->toggle($class);
        }
        return $this;
    }

    /**
     * Sets the 'id' attribute.
     *
     * @param string $id The ID value.
     */
    public function setId(string $id): self
    {
        return $this->setAttribute('id', $id);
    }

    /**
     * Gets the 'id' attribute.
     *
     */
    public function getId(): ?string
    {
        return $this->getAttribute('id');
    }

    /**
     * Sets a generic attribute.
     *
     * @param string $qualifiedName The name of the attribute.
     * @param string $value The value of the attribute.
     */
    public function setAttribute(string $qualifiedName, string $value): self
    {
        $this->attributes[$qualifiedName] = $value;
        return $this;
    }

    /**
     * Removes an attribute.
     *
     * @param string $qualifiedName The name of the attribute to remove.
     */
    public function removeAttribute(string $qualifiedName): self
    {
        unset($this->attributes[$qualifiedName]);
        return $this;
    }

    /**
     * Checks if an attribute exists.
     *
     * @param string $qualifiedName The name of the attribute.
     */
    public function hasAttribute(string $qualifiedName): bool
    {
        return \array_key_exists($qualifiedName, $this->attributes);
    }

    /**
     * Gets the value of a specific attribute.
     *
     * @param string $qualifiedName The name of the attribute.
     */
    public function getAttribute(string $qualifiedName): ?string
    {
        return $this->attributes[$qualifiedName] ?? null;
    }

    /**
     * Sets a boolean attribute.
     *
     * @param string $qualifiedName The name of the boolean attribute.
     * @param bool $value The boolean value.
     */
    public function setBooleanAttribute(string $qualifiedName, bool $value = true): self
    {
        if ($value) {
            $this->setAttribute($qualifiedName, $qualifiedName);
        } else {
            $this->removeAttribute($qualifiedName);
        }
        return $this;
    }

    /**
     * Sets the 'disabled' boolean attribute.
     *
     */
    public function disabled(bool $isDisabled = true): self
    {
        return $this->setBooleanAttribute('disabled', $isDisabled);
    }

    /**
     * Sets the 'checked' boolean attribute.
     *
     */
    public function checked(bool $isChecked = true): self
    {
        return $this->setBooleanAttribute('checked', $isChecked);
    }

    /**
     * Sets a 'data-*' attribute.
     *
     * @param string $key The key (without 'data-').
     * @param string $value The value.
     */
    public function setDataAttribute(string $key, string $value): self
    {
        return $this->setAttribute('data-' . $key, $value);
    }

    /**
     * Gets a 'data-*' attribute.
     *
     * @param string $key The key (without 'data-').
     */
    public function getDataAttribute(string $key): ?string
    {
        return $this->getAttribute('data-' . $key);
    }

    /**
     * Sets an 'aria-*' attribute.
     *
     * @param string $key The key (without 'aria-').
     * @param string $value The value.
     */
    public function setAriaAttribute(string $key, string $value): self
    {
        return $this->setAttribute('aria-' . $key, $value);
    }

    /**
     * Gets an 'aria-*' attribute.
     *
     * @param string $key The key (without 'aria-').
     */
    public function getAriaAttribute(string $key): ?string
    {
        return $this->getAttribute('aria-' . $key);
    }

    /**
     * Returns an iterator for all attributes.
     *
     * @return Iterator<string, string>
     */
    public function iterAttributes(): Iterator
    {
        return new ArrayIterator($this->attributes);
    }
}
