<?php namespace App\Entities;


class Product extends BaseEntity
{
    /**
     * @var string
     */
    protected $table = 'products';

    /**
     * @var array
     */
    protected $validation = array(
        // DO NOT EDIT
        'title' => 'required',
        'description' => '',
        'price' => 'required|min:0',
        'image' => 'required',
        'category' => 'required',
        'created_by' => 'required',
        // /DO NOT EDIT
    );
}
