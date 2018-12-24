<?php
/**
 * Created by PhpStorm.
 * User: cyrilnxumalo
 * Date: 2018/12/24
 * Time: 11:47
 */

namespace App\Validation;

use App\Exceptions\BadRequestException;
use Illuminate\Support\Facades\Validator as BaseValidator;

class Validator extends BaseValidator
{
    /**
     * @param array $data
     * @param array $rules
     * @return array
     * @throws BadRequestException
     */
    public static function validate(array $data, array $rules) {
        $v = Validator::make($data, $rules);

        if ($v->fails()) {
            $failed = $v->failed();
            $messages = array();
            $fields = array();

            foreach ($v->getMessageBag()->getMessages() as $k => $v) {
                foreach ($v as $m) {
                    if ( ! in_array($k, $fields)) {
                        // we only want the first error per field
                        $messages[] = array(
                            'field' => $k,
                            'reason' => snake_case(array_keys($failed[$k])[0]),
                            'error' => $m,
                            'value' => isset($data[$k]) ? $data[$k] : ''
                        );
                        $fields[] = $k;
                    }
                }
            }

            throw new BadRequestException($messages);
        }

        return $data;
    }
}
