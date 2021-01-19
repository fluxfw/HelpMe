<?php

namespace srag\RequiredData\HelpMe\Fill;

use srag\DIC\HelpMe\DICTrait;
use srag\RequiredData\HelpMe\Field\AbstractField;
use srag\RequiredData\HelpMe\Fill\Form\FormBuilder;
use srag\RequiredData\HelpMe\Utils\RequiredDataTrait;

/**
 * Class Factory
 *
 * @package srag\RequiredData\HelpMe\Fill
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Factory
{

    use DICTrait;
    use RequiredDataTrait;

    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Factory constructor
     */
    private function __construct()
    {

    }


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @param AbstractField $field
     *
     * @return AbstractFillField
     */
    public function newFillFieldInstance(AbstractField $field) : AbstractFillField
    {
        $class = substr(get_class($field), 0, -5) . "FillField";

        $fill_field = new $class($field);

        return $fill_field;
    }


    /**
     * @return FillStorage
     *
     * @internal
     */
    public function newFillStorageInstance() : FillStorage
    {
        $fill_storage = new FillStorage();

        return $fill_storage;
    }


    /**
     * @param AbstractFillCtrl $parent
     *
     * @return FormBuilder
     */
    public function newFormBuilderInstance(AbstractFillCtrl $parent) : FormBuilder
    {
        $form = new FormBuilder($parent);

        return $form;
    }
}
