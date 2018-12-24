<?php
/**
 * Created by PhpStorm.
 * User: cyrilnxumalo
 * Date: 2018/12/24
 * Time: 13:48
 */

namespace App\Http\Controllers\Api\Version1;


use App\Entities\Category;
use App\Exceptions\BadRequestException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Categories;
use Illuminate\Http\Exceptions\HttpResponseException;

class CategoryController extends Controller
{
    /**
     * @var Categories
     */
    protected $categoriesModel;

    public function __construct(Categories $categoriesModel)
    {
        $this->categoriesModel = $categoriesModel;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategories()
    {
        $categories = $this->categoriesModel->findAllCategories();
        return response()->json($categories->toArray());
    }

    /**
     * @param CategoryRequest $categoryRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCategory(CategoryRequest $categoryRequest)
    {
        try {
            $category = Category::create($categoryRequest->toArray());
            return response()->json($category->toArray());
        } catch (BadRequestException $e) {
            throw new HttpResponseException(response()->json($e->toArray(), $e->getCode()));
        }
    }
}
