<?php

namespace srag\JiraCurl\HelpMe\Exception;

use ilException;

/**
 * Class JiraCurlException
 *
 * @package srag\JiraCurl\HelpMe\Exception
 */
class JiraCurlException extends ilException
{

    /**
     * JiraCurlException constructor
     *
     * @param string $message
     * @param int    $code
     *
     * @internal
     */
    public function __construct(string $message, int $code = 0)
    {
        parent::__construct($message, $code);
    }
}
