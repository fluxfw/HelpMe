<?php

namespace srag\RequiredData\HelpMe\Field\Field\StaticMultiSearchSelect;

require_once __DIR__ . "/../../../../../../autoload.php";

use srag\CustomInputGUIs\HelpMe\MultiSelectSearchNewInputGUI\AbstractAjaxAutoCompleteCtrl;
use srag\RequiredData\HelpMe\Field\FieldCtrl;
use srag\RequiredData\HelpMe\Utils\RequiredDataTrait;

/**
 * Class SMSSAjaxAutoCompleteCtrl
 *
 * @package srag\RequiredData\HelpMe\Field\Field\StaticMultiSearchSelect
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class SMSSAjaxAutoCompleteCtrl extends AbstractAjaxAutoCompleteCtrl
{

    use RequiredDataTrait;

    /**
     * @var FieldCtrl
     */
    protected $parent;


    /**
     * SMSSAjaxAutoCompleteCtrl constructor
     *
     * @param FieldCtrl $parent
     */
    public function __construct(FieldCtrl $parent)
    {
        parent::__construct();

        $this->parent = $parent;
    }


    /**
     * @inheritDoc
     */
    public function fillOptions(array $ids) : array
    {
        $form = self::requiredData()->fields()->factory()->newFormBuilderInstance($this->parent, $this->parent->getField());

        return $form->getAjaxAutoCompleteCtrl()->fillOptions($ids);
    }


    /**
     * @inheritDoc
     */
    public function searchOptions(/*?*/ string $search = null) : array
    {
        $form = self::requiredData()->fields()->factory()->newFormBuilderInstance($this->parent, $this->parent->getField());

        return $form->getAjaxAutoCompleteCtrl()->searchOptions($search);
    }
}
