<?php namespace App\Entities;

use Illuminate\Support\Collection;

class BaseEntityCollection extends Collection {

    /**
     * @var string
     */
    protected $className;

    /**
     * @param array $items
     * @param string $className
     */
    public function __construct(array $items, $className = null)
    {
        $this->className = $className;
        $items = array_map(function ($result) {
            return $this->toObject($result);
        }, $items);
        parent::__construct($items);
    }

    /**
     * @param mixed $key
     * @param mixed $value
     */
    public function put($key, $value)
    {
        parent::put($key, $this->toObject($value));
    }

    /**
     * Push an item onto the end of the collection.
     *
     * @param mixed $value
     * @return void
     */
    public function push($value)
    {
        // laravel has push and prepend mixed up in our version of vendor package
        $this->items[] = $this->toObject($value);
    }

    /**
     * Push an item onto the beginning of the collection.
     *
     * @param mixed $value
     * @param array $key
     * @return void
     */
    public function prepend($value, $key = null)
    {
        array_unshift($this->items, $this->toObject($value));
    }

    /**
     * @param array $args
     * @return array
     */
    public function toArray(array $args = array())
    {
        return array_map(function($value) use ($args) {
            if (is_callable(array($value, 'toArray'))) {
                /** @var BaseEntity $value */
                return $value->toArray($args);
            } else {
                return (array) $value;
            }
        }, $this->items);
    }

    /**
     * @param \stdClass $result
     * @return \stdClass
     */
    protected function toObject($result)
    {
        $className = $this->className; /** @var $className BaseEntity */
        if ($className && $result instanceof $className) {
            return $result; // result is already the entity - nothing to do
        }
        if ($className) {
            return new $className($result);
        } else {
            return $result;
        }
    }
}
