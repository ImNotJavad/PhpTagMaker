<?php declare(strict_types=1);

namespace AhjDev\PhpTagMaker\Node\Internal;

use DOMAttr;
use DOMElement;
use Iterator;
use ArrayIterator;
use AhjDev\PhpTagMaker\HtmlClass;

/**
 * @internal
 * @property DOMElement $domElement
 * @property HtmlClass $class // Ensure HtmlTag has a public or accessible $class property
 */
trait Attributes
{
    public function setClass(string ...$classes): self
    {
        // If the HtmlTag class itself manages an HtmlClass instance for its 'class' attribute
        if (isset($this->class) && $this->class instanceof HtmlClass) {
            $this->class = new HtmlClass(...$classes);
        }
        // Always set the attribute on the DOMElement for consistency during toDomNode
        $this->domElement->setAttribute('class', (string)(new HtmlClass(...$classes)));
        return $this;
    }

    public function getClass(): null|string|array
    {
        if ($attribute = $this->getAttribute('class')) {
            $attribute = explode(' ', $attribute);
            return count($attribute) === 1  ? $attribute[0] : $attribute;
        }
        return null;
    }

    /**
     * Adds one or more classes to the class attribute.
     * This method should ideally operate on an HtmlClass instance if available.
     */
    public function addClass(string ...$classes): self
    {
        if (isset($this->class) && $this->class instanceof HtmlClass) {
            foreach ($classes as $class) {
                $this->class->add($class);
            }
            $this->domElement->setAttribute('class', (string)$this->class);
        } else {
            // Fallback if no HtmlClass instance, or create one
            $currentClasses = $this->getAttribute('class') ?? '';
            $currentClassArray = $currentClasses ? explode(' ', $currentClasses) : [];
            $newClasses = new HtmlClass(...array_merge($currentClassArray, $classes));
            $this->domElement->setAttribute('class', (string)$newClasses);
        }
        return $this;
    }

    /**
     * Removes one or more classes from the class attribute.
     * This method should ideally operate on an HtmlClass instance if available.
     */
    public function removeClass(string ...$classes): self
    {
        if (isset($this->class) && $this->class instanceof HtmlClass) {
            foreach ($classes as $class) {
                $this->class->remove($class);
            }
            $this->domElement->setAttribute('class', (string)$this->class);
        } else {
            $currentClasses = $this->getAttribute('class') ?? '';
            if ($currentClasses) {
                $currentClassArray = explode(' ', $currentClasses);
                $updatedClasses = array_diff($currentClassArray, $classes);
                $this->domElement->setAttribute('class', implode(' ', $updatedClasses));
            }
        }
         if (empty($this->domElement->getAttribute('class'))) {
            $this->domElement->removeAttribute('class');
        }
        return $this;
    }

    /**
     * Toggles one or more classes in the class attribute.
     * This method should ideally operate on an HtmlClass instance if available.
     */
    public function toggleClass(string ...$classes): self
    {
        if (isset($this->class) && $this->class instanceof HtmlClass) {
            foreach ($classes as $class) {
                $this->class->toggle($class); // Assumes HtmlClass has a toggle method
            }
            $this->domElement->setAttribute('class', (string)$this->class);
        } else {
             // Fallback, less efficient for multiple toggles
            $currentClasses = $this->getAttribute('class') ?? '';
            $currentClassArray = $currentClasses ? explode(' ', $currentClasses) : [];
            foreach ($classes as $class) {
                $class = trim($class);
                if (in_array($class, $currentClassArray, true)) {
                    $currentClassArray = array_diff($currentClassArray, [$class]);
                } else {
                    $currentClassArray[] = $class;
                }
            }
            $newClassString = implode(' ', array_filter(array_unique($currentClassArray)));
            if ($newClassString) {
                $this->domElement->setAttribute('class', $newClassString);
            } else {
                $this->domElement->removeAttribute('class');
            }
        }
        if (empty($this->domElement->getAttribute('class'))) {
            $this->domElement->removeAttribute('class');
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
        $this->domElement->setAttribute($qualifiedName, $value);
        return $this;
    }

    public function removeAttribute(string $qualifiedName): self
    {
        $this->domElement->removeAttribute($qualifiedName);
        return $this;
    }
    public function hasAttribute(string $qualifiedName): bool
    {
        return $this->domElement->hasAttribute($qualifiedName);
    }

    public function getAttribute(string $qualifiedName): ?string
    {
        $attribute = $this->domElement->getAttribute($qualifiedName);
        // DOMElement::getAttribute returns empty string for non-existent attributes,
        // aligning with null for "truly not set".
        return $this->domElement->hasAttribute($qualifiedName) ? $attribute : null;
    }

    /**
     * Sets a boolean attribute.
     * If value is true, the attribute is set (e.g., <input disabled> which means disabled="disabled").
     * If value is false, the attribute is removed.
     * @param string $qualifiedName The name of the attribute (e.g., "disabled", "checked").
     * @param bool $value The value of the attribute.
     */
    public function setBooleanAttribute(string $qualifiedName, bool $value = true): self
    {
        if ($value) {
            // Standard way for boolean attributes is to set the attribute name as its value, or empty string
            $this->domElement->setAttribute($qualifiedName, $qualifiedName);
        } else {
            $this->domElement->removeAttribute($qualifiedName);
        }
        return $this;
    }

    /**
     * Helper method to set the 'disabled' boolean attribute.
     * @param bool $isDisabled
     */
    public function disabled(bool $isDisabled = true): self
    {
        return $this->setBooleanAttribute('disabled', $isDisabled);
    }

    /**
     * Helper method to set the 'checked' boolean attribute.
     * @param bool $isChecked
     */
    public function checked(bool $isChecked = true): self
    {
        return $this->setBooleanAttribute('checked', $isChecked);
    }

    /**
     * Sets a data attribute (data-*).
     * @param string $key The key of the data attribute (without "data-").
     * @param string $value The value of the data attribute.
     */
    public function setDataAttribute(string $key, string $value): self
    {
        $this->domElement->setAttribute('data-' . $key, $value);
        return $this;
    }

    /**
     * Gets a data attribute (data-*).
     * @param string $key The key of the data attribute (without "data-").
     * @return string|null The value of the attribute or null if not set.
     */
    public function getDataAttribute(string $key): ?string
    {
        return $this->getAttribute('data-' . $key);
    }

    /**
     * Removes a data attribute (data-*).
     * @param string $key The key of the data attribute (without "data-").
     */
    public function removeDataAttribute(string $key): self
    {
        $this->domElement->removeAttribute('data-' . $key);
        return $this;
    }

    /**
     * Checks if a data attribute (data-*) exists.
     * @param string $key The key of the data attribute (without "data-").
     */
    public function hasDataAttribute(string $key): bool
    {
        return $this->domElement->hasAttribute('data-' . $key);
    }

    /**
     * Sets an ARIA attribute (aria-*).
     * @param string $key The key of the ARIA attribute (without "aria-").
     * @param string $value The value of the ARIA attribute.
     */
    public function setAriaAttribute(string $key, string $value): self
    {
        $this->domElement->setAttribute('aria-' . $key, $value);
        return $this;
    }

    /**
     * Gets an ARIA attribute (aria-*).
     * @param string $key The key of the ARIA attribute (without "aria-").
     * @return string|null The value of the attribute or null if not set.
     */
    public function getAriaAttribute(string $key): ?string
    {
        return $this->getAttribute('aria-' . $key);
    }

    /**
     * Removes an ARIA attribute (aria-*).
     * @param string $key The key of the ARIA attribute (without "aria-").
     */
    public function removeAriaAttribute(string $key): self
    {
        $this->domElement->removeAttribute('aria-' . $key);
        return $this;
    }

    /**
     * Checks if an ARIA attribute (aria-*) exists.
     * @param string $key The key of the ARIA attribute (without "aria-").
     */
    public function hasAriaAttribute(string $key): bool
    {
        return $this->domElement->hasAttribute('aria-' . $key);
    }

    /**
     * @return ArrayIterator<int, DOMAttr>
     */
    public function iterAttributes(): Iterator
    {
        $attributes = [];
        if ($this->domElement->hasAttributes()) {
            foreach ($this->domElement->attributes as $attr) {
                // Filter out null attributes which can happen with namedNodeMap
                if ($attr !== null) {
                    $attributes[] = $attr;
                }
            }
        }
        return new ArrayIterator($attributes);
    }
}