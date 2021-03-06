<?php

namespace ReninsApi\Request;

class ContainerCollection implements \Iterator
{
    /**
     * @var Container[]
     */
    protected $items = [];

    /**
     * @var int
     */
    protected $position = 0;

    protected $containerClass;

    /**
     * @param Container[] $data
     */
    public function __construct(array $data = [])
    {
        foreach($data as $item) {
            $this->add($item);
        }
    }

    /**
     * Create instance from xml
     * @param \SimpleXMLElement $children
     * @param string $containerClass
     * @return static|null
     */
    public static function createFromXml(\SimpleXMLElement $children, string $containerClass) {
        if ($children && $children->count()) {
            $coll = new static();
            $coll->fromXml($children, $containerClass);
            return $coll;
        }
        return null;
    }

    /**
     * Create instance from array of object or one object
     * @param object[]|object $object - array of object
     * @param string $containerClass
     * @return static|null
     */
    public static function createFromObject($object, string $containerClass) {
        if ($object) {
            $coll = new static();
            $coll->fromObject($object, $containerClass);
            return $coll;
        }
        return null;
    }

    /**
     * Add container
     * @param Container $container
     * @return $this
     */
    public function add(Container $container) {
        $containerClass = get_class($container);

        if ($this->containerClass) {
            if ($containerClass != $this->containerClass) {
                throw new ContainerCollectionException("Collection can't store items of different classes ({$containerClass} != {$this->containerClass})");
            }
        } else {
            $this->containerClass = $containerClass;
        }

        $this->items[] = $container;
        return $this;
    }

    /**
     * Get item by index
     * @param int $index
     * @return Container
     */
    public function get($index): Container {
        if ($index < 0 || $index >= count($this->items)) {
            throw new ContainerCollectionException("Invalid index");
        }
        return $this->items[$index];
    }

    /**
     * Clear
     * @return $this
     */
    public function clear() {
        $this->items = [];
        $this->position = 0;
        $this->containerClass = null;
        return $this;
    }

    /**
     * Get count of items
     * @return int
     */
    public function count() {
        return count($this->items);
    }

    /**
     * Validate each item
     * @return array
     */
    public function validate() {
        $errors = [];

        foreach($this->items as $index => $item) {
            $errorsVal = $item->validate();
            foreach ($errorsVal as $propertyVal => $errorVal) {
                $errors[$index . '.' . $propertyVal] = $errorVal;
            }
        }

        return $errors;
    }

    /**
     * Find item by property
     * @param $property
     * @param $value
     * @return Container
     */
    public function find($property, $value):Container {
        foreach ($this->items as $item) {
            if ($item->{$property} == $value) {
                return $item;
            }
        }
        return null;
    }

    /**
     * Get array of items recursively
     * @return array
     */
    public function toArray(): array
    {
        $ret = [];
        foreach($this->items as $item) {
            $ret[] = $item->toArray();
        }
        return $ret;
    }

    /**
     * Get XML items recursively
     * @param \SimpleXMLElement $xml
     * @param string $tagName
     * @return $this
     */
    public function toXml(\SimpleXMLElement $xml, string $tagName) {
        if ($tagName == '') {
            throw new \InvalidArgumentException("Tag name isn't specified");
        }

        foreach($this->items as $item) {
            $added = $xml->addChild($tagName);
            $item->toXml($added);
        }

        return $this;
    }

    /**
     * Fill collection from xml children tags
     * @param \SimpleXMLElement $children - children tags
     * @param string $containerClass
     * @return $this
     */
    public function fromXml(\SimpleXMLElement $children, string $containerClass) {
        if ($children && $children->count()) {
            foreach ($children as $child) {
                /* @var Container $cont */
                $cont = new $containerClass();
                $cont->fromXml($child);
                $this->add($cont);
            }
        }
        return $this;
    }

    /**
     * Fill collection from array of objects or one object
     * @param object[]|object $object - array of objects
     * @param string $containerClass
     * @return $this
     */
    public function fromObject($object, string $containerClass) {
        if ($object) {
            if (is_array($object)) {
                foreach ($object as $obj) {
                    /* @var Container $cont */
                    $cont = new $containerClass();
                    $cont->fromObject($obj);
                    $this->add($cont);
                }
            } else {
                /* @var Container $cont */
                $cont = new $containerClass();
                $cont->fromObject($object);
                $this->add($cont);
            }
        }
        return $this;
    }

    /*
     * ITERATOR
     */

    public function rewind() {
        $this->position = 0;
    }

    /**
     * @return Container
     */
    public function current() {
        return $this->items[$this->position];
    }

    /**
     * @return int
     */
    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    /**
     * @return bool
     */
    public function valid() {
        return isset($this->items[$this->position]);
    }
}