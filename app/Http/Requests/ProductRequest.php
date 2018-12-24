<?php namespace App\Http\Requests;


class ProductRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => '',
            'description' => '',
            'price' => '',
            'image' => '',
            'category' => '',
            'created_by' => ''
        ];
    }
}
