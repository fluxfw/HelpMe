<?php

namespace srag\RequiredData\HelpMe\Field\Table;

use srag\DataTableUI\HelpMe\Component\Data\Data;
use srag\DataTableUI\HelpMe\Component\Data\Row\RowData;
use srag\DataTableUI\HelpMe\Component\Settings\Settings;
use srag\DataTableUI\HelpMe\Implementation\Data\Fetcher\AbstractDataFetcher;
use srag\RequiredData\HelpMe\Field\AbstractField;
use srag\RequiredData\HelpMe\Field\FieldsCtrl;
use srag\RequiredData\HelpMe\Utils\RequiredDataTrait;

/**
 * Class DataFetcher
 *
 * @package srag\RequiredData\HelpMe\Field\Table
 */
class DataFetcher extends AbstractDataFetcher
{

    use RequiredDataTrait;

    /**
     * @var FieldsCtrl
     */
    protected $parent;


    /**
     * @inheritDoc
     */
    public function __construct(FieldsCtrl $parent)
    {
        $this->parent = $parent;

        parent::__construct();
    }


    /**
     * @inheritDoc
     */
    public function fetchData(Settings $settings) : Data
    {
        $data = self::requiredData()->fields()->getFields($this->parent->getParentContext(), $this->parent->getParentId(), null, false);

        $max_count = count($data);

        $data = array_slice($data, $settings->getOffset(), $settings->getRowsCount());

        return self::dataTableUI()->data()->data(array_map(function (AbstractField $field
        ) : RowData {
            return self::dataTableUI()->data()->row()->getter($field->getId(), $field);
        }, $data), $max_count);
    }
}
