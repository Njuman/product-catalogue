<?php namespace App\Entities;

use Exception;
use App\Exceptions\BadRequestException;
use App\Validation\Validator;
use Illuminate\Support\Facades\DB;

class BaseEntity
{
    /**
     * @var array
     */
    protected $attributes = array();

    /**
     * This array can be used to store any in-memory optimizations
     *
     * @var array
     */
    protected $cache = array();

    /**
     * @var string
     */
    protected $table;

    /**
     * @var array
     */
    protected $validation = array();

    /**
     * Constructs a new BaseEntity.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->setAttributes($attributes);
    }

    /**
     * Sets attributes on the current entity.
     *
     * @param array $attributes
     * @return BaseEntity
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = array_merge($this->attributes, $attributes);
        return $this;
    }

    /**
     * Sets an attribute on this entity.
     *
     * @param string $name
     * @param mixed $value
     * @return BaseEntity
     */
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    /**
     * Returns the attributes of the current entity.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if ( ! isset($this->attributes[$name])) {
            return null;
        }
        return $this->attributes[$name];
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->setAttribute($name, $value);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->attributes[$name]);
    }

    /**
     * @param array $args
     * @return array
     */
    public function toArray(array $args = array())
    {
        $return = $this->getAttributes();
        ksort($return);
        return $return;
    }

    /**
     * Persists the result of $func to this entity for the duration of its life
     *
     * @param string $key
     * @param Callable $func
     * @return mixed
     */
    protected function persist($key, $func)
    {
        if ( ! isset($this->cache[$key])) {
            $this->cache[$key] = $func();
        }
        return $this->cache[$key];
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function inject($key, $value)
    {
        $this->cache[$key] = $value;
    }

    /**
     * @return string
     */
    public function getTable() {
        return $this->table;
    }

    /**
     * @return array
     */
    public function getRules() {
        return $this->validation;
    }

    /**
     * @param $data
     * @return BaseEntity
     * @throws BadRequestException
     */
    public static function create($data) {
        try {
            $obj = new static($data); /** @var $obj BaseEntity */
            $obj->save();
            return $obj;
        } catch (Exception $e) {
            throw new BadRequestException();
        }
    }

    /**
     * @param $data
     * @return BaseEntity
     * @throws BadRequestException
     */
    public function update($data) {
        try {
            $this->setAttributes($data);
            $this->save();
            return $this;
        } catch (Exception $e) {
            throw new BadRequestException();
        }
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function save()
    {
        DB::connection()->beginTransaction();
        try {
            $data = $this->validate();
            if ($this->id) {
                $data['updated_at'] = date('Y-m-d H:i:s', time());
                DB::connection()->table($this->getTable())->where('id', $this->id)->update($data);
            } else {
                $data['created_at'] = date('Y-m-d H:i:s', time());
                $id = DB::connection()->table($this->getTable())->insertGetId($data);
                $this->id = $id;
            }
            DB::connection()->commit();
            return $this;
        } catch (Exception $e) {
            DB::connection()->rollBack();
            throw $e;
        }
    }

    /**
     * @return array
     * @throws BadRequestException
     */
    public function validate()
    {   try {
            return Validator::validate($this->getAttributes(), $this->getRules());
        } catch (BadRequestException $e) {
            throw $e;
        }
    }
}
