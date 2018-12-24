<?php
/**
 * Created by PhpStorm.
 * User: cyrilnxumalo
 * Date: 2018/12/24
 * Time: 13:39
 */

namespace App\Entities;


class Category extends BaseEntity
{
    /**
     * @var string
     */
    protected $table = 'categories';

    /**
     * @var array
     */
    protected $validation = array(
        // DO NOT EDIT
        'name' => 'required'
        // /DO NOT EDIT
    );
}
