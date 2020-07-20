<?php

namespace srag\DataTableUI\HelpMe\Implementation\Format;

use ilHtmlToPdfTransformerFactory;
use srag\DataTableUI\HelpMe\Component\Table;

/**
 * Class PdfFormat
 *
 * @package srag\DataTableUI\HelpMe\Implementation\Format
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class PdfFormat extends HtmlFormat
{

    /**
     * @inheritDoc
     */
    public function getFormatId() : string
    {
        return self::FORMAT_PDF;
    }


    /**
     * @inheritDoc
     */
    protected function getFileExtension() : string
    {
        return "pdf";
    }


    /**
     * @inheritDoc
     */
    protected function renderTemplate(Table $component) : string
    {
        $html = parent::renderTemplate($component);

        $pdf = new ilHtmlToPdfTransformerFactory();

        $tmp_file = $pdf->deliverPDFFromHTMLString($html, "", ilHtmlToPdfTransformerFactory::PDF_OUTPUT_FILE, self::class, $component->getTableId());

        $data = file_get_contents($tmp_file);

        unlink($tmp_file);

        return $data;
    }
}
