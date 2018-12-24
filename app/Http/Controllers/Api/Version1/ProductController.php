<?php namespace App\Http\Controllers\Api\Version1;

use App\Entities\Product;
use App\Exceptions\BadRequestException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Products;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductController extends Controller
{
    /**
     * @var Products
     */
    protected $productModel;

    public function __construct(Products $productModel)
    {
        $this->productModel = $productModel;
    }

    public function getProducts()
    {
        $products = $this->productModel->findAllProducts();
        return response()->json($products->toArray());
    }

    /**
     * @param ProductRequest $productRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function postProducts(ProductRequest $productRequest)
    {
        try {
            $productRequest = $productRequest->all();
            $productRequest['created_by'] = 3;
            $productRequest['image'] = 'https://via.placeholder.com/150/000000/FFFFFF/?text=IPaddress.net';
            $product = Product::create($productRequest);
            return response()->json($product->toArray());
        } catch (BadRequestException $e) {
            throw new HttpResponseException(response()->json($e->toArray(), $e->getCode()));
        }
    }

    /**
     * @param $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProduct($productId)
    {
        $product = $this->productModel->findById($productId);
        return response()->json($product->toArray());
    }

    /**
     * @param $productId
     * @param ProductRequest $productRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function putProducts($productId, ProductRequest $productRequest)
    {
        $product = $this->productModel->findById($productId);

        try {
            $product->update($productRequest->toArray());
            return response()->json($product->toArray());
        } catch (BadRequestException $e) {
            throw new HttpResponseException(response()->json($e->toArray(), $e->getCode()));
        }
    }
}
