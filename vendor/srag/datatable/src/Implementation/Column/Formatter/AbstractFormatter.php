<?php

namespace srag\DataTableUI\HelpMe\Implementation\Column\Formatter;

use srag\DataTableUI\HelpMe\Component\Column\Formatter\Formatter;
use srag\DataTableUI\HelpMe\Implementation\Utils\DataTableUITrait;
use srag\DIC\HelpMe\DICTrait;

/**
 * Class AbstractFormatter
 *
 * @package srag\DataTableUI\HelpMe\Implementation\Column\Formatter
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractFormatter implements Formatter
{

    use DICTrait;
    use DataTableUITrait;


    /**
     * AbstractFormatter constructor
     */
    public function __construct()
    {

    }
}
