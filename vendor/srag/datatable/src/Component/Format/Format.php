<?php

namespace srag\DataTableUI\HelpMe\Component\Format;

use srag\DataTableUI\HelpMe\Component\Data\Data;
use srag\DataTableUI\HelpMe\Component\Settings\Settings;
use srag\DataTableUI\HelpMe\Component\Table;

/**
 * Interface Format
 *
 * @package srag\DataTableUI\HelpMe\Component\Format
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Format
{

    /**
     * @var string
     */
    const FORMAT_BROWSER = "browser";
    /**
     * @var string
     */
    const FORMAT_CSV = "csv";
    /**
     * @var string
     */
    const FORMAT_EXCEL = "excel";
    /**
     * @var string
     */
    const FORMAT_HTML = "html";
    /**
     * @var string
     */
    const FORMAT_PDF = "pdf";
    /**
     * @var int
     */
    const OUTPUT_TYPE_DOWNLOAD = 2;
    /**
     * @var int
     */
    const OUTPUT_TYPE_PRINT = 1;


    /**
     * @param string $data
     * @param Table  $component
     */
    public function deliverDownload(string $data, Table $component) : void;


    /**
     * @param Table $component
     *
     * @return string
     */
    public function getDisplayTitle(Table $component) : string;


    /**
     * @return string
     */
    public function getFormatId() : string;


    /**
     * @return int
     */
    public function getOutputType() : int;


    /**
     * @return object
     */
    public function getTemplate() : object;


    /**
     * @param Table     $component
     * @param Data|null $data
     * @param Settings  $settings
     *
     * @return string
     */
    public function render(Table $component, ?Data $data, Settings $settings) : string;
}
