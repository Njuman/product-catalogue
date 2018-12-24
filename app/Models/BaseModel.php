<?php
/**
 * Created by PhpStorm.
 * User: cyrilnxumalo
 * Date: 2018/12/21
 * Time: 17:18
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * @var array
     */
    protected static $cache = array();

    /**
     * @param string $key
     * @param Callable $func
     * @return mixed
     */
    protected static function persist($key, $func)
    {
        if ( ! isset(self::$cache[$key])) {
            self::$cache[$key] = $func();
        }
        return self::$cache[$key];
    }
}
