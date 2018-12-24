<?php namespace App\Exceptions;

class BadRequestException extends \Exception {

    /**
     * @var int
     */
    protected $code = 400;

    /**
     * @var string
     */
    protected $message = 'Bad Request';

    /**
     * @var array
     */
    protected $errors;


    /**
     * BadRequestException constructor.
     * @param array $errors
     */
    public function __construct(array $errors = array())
    {
        $this->errors = $errors;
        $this->message .= sprintf(': %s', $this->formatErrors());
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'code' => $this->code,
            'message' => $this->message,
        );
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return string
     */
    public function formatErrors()
    {
        $lines = array();
        foreach ($this->errors as $error) {
            if (is_array($error['value'])) {
                $value = '[array]';
            } else if (is_object($error['value'])) {
                $value = '[object]';
            } else {
                $value = $error['value'];
            }

            $lines[] = sprintf("%s [value='%s']", $error['error'], $value);
        }
        return implode("\n", $lines);
    }
}
