<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator as BaseValidator;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class ApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    abstract public function rules();

    /**
     * Get the proper failed validation response for the request.
     *
     * @param array $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        return new JsonResponse(['errors' => $errors], 422);
    }

    /**
     * @param null $keys
     * @return array
     */
    public function all($keys = null)
    {
        $data = parent::all();
        $permitted = $this->getPermittedFields();
        return $this->filterInputArr($data, $permitted);
    }

    /**
     * @param string|null $root
     * @return array
     */
    protected function getPermittedFields($root = null)
    {
        $keys = array_keys($this->rules());
        $normalised = [];
        foreach ($keys as $k) {
            if (preg_match('/^(.+)\.\*\.(.+)$/', $k, $matches)) {
                // this will catch users.*.first_name
                $k = $matches[1] . '[]';
                $field = $matches[2];
                if (!isset($normalised[$k])) {
                    $normalised[$k] = [];
                }
                $normalised[$k][$field] = [];
            } elseif (preg_match('/^(.+)\.\*$/', $k, $matches)) {
                // this will catch users.first_name
                $k = $matches[1];
                $normalised[$k] = [];
            } elseif (preg_match('/^(.+)\.(.+)$/', $k, $matches)) {
                //. this will catch simple keys
                $k = $matches[1];
                $field = $matches[2];
                if (!isset($normalised[$k])) {
                    $normalised[$k] = [];
                }
                $normalised[$k][$field] = [];
            } else {
                $normalised[$k] = [];
            }
        }

        if ($root === null) {
            return $normalised;
        } else {
            return array_get($normalised, $root, []);
        }
    }

    /**
     * @param array $arr
     * @param array $permitted
     * @return array
     */
    protected function filterInputArr($arr, array $permitted)
    {
        $filtered = [];
        foreach ($arr as $k => $v) {
            if (!isset($permitted[$k])) {
                continue;
            }

            if (is_array($v)) {
                if (isset($permitted[$k . '[]'])) {
                    $nestedPermitted = $this->getPermittedFields($k . '[]');
                    $v = array_map(function ($nested) use ($nestedPermitted) {
                        return $this->filterInputArr($nested, $nestedPermitted);
                    }, $v);
                } else {
                    $v = $this->filterInputArr($v, $this->getPermittedFields($k));
                }
            }

            $filtered[$k] = $v;
        }

        return $filtered;
    }

    protected function failedValidation(BaseValidator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
