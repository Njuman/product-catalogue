<?php namespace App\Models;

use App\Entities\BaseEntityCollection;

class Categories extends BaseModel
{
    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @return BaseEntityCollection
     */
    public function findAllCategories() {
        $attr = static::all()->toArray();
        return new BaseEntityCollection($attr, 'App\Entities\Category');
    }
}
