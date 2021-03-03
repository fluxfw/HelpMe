<?php

namespace srag\RequiredData\HelpMe\Field;

use srag\DIC\HelpMe\DICTrait;
use srag\RequiredData\HelpMe\Field\Field\Checkbox\CheckboxField;
use srag\RequiredData\HelpMe\Field\Field\Date\DateField;
use srag\RequiredData\HelpMe\Field\Field\Email\EmailField;
use srag\RequiredData\HelpMe\Field\Field\Float\FloatField;
use srag\RequiredData\HelpMe\Field\Field\Group\GroupField;
use srag\RequiredData\HelpMe\Field\Field\Integer\IntegerField;
use srag\RequiredData\HelpMe\Field\Field\MultilineText\MultilineTextField;
use srag\RequiredData\HelpMe\Field\Field\MultiSearchSelect\MultiSearchSelectField;
use srag\RequiredData\HelpMe\Field\Field\MultiSelect\MultiSelectField;
use srag\RequiredData\HelpMe\Field\Field\Radio\RadioField;
use srag\RequiredData\HelpMe\Field\Field\SearchSelect\SearchSelectField;
use srag\RequiredData\HelpMe\Field\Field\Select\SelectField;
use srag\RequiredData\HelpMe\Field\Field\Text\TextField;
use srag\RequiredData\HelpMe\Field\Form\AbstractFieldFormBuilder;
use srag\RequiredData\HelpMe\Field\Form\CreateFieldFormBuilder;
use srag\RequiredData\HelpMe\Field\Table\TableBuilder;
use srag\RequiredData\HelpMe\Utils\RequiredDataTrait;

/**
 * Class Factory
 *
 * @package srag\RequiredData\HelpMe\Field
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
     * @var array
     */
    protected $classes
        = [
            CheckboxField::class,
            DateField::class,
            EmailField::class,
            FloatField::class,
            GroupField::class,
            IntegerField::class,
            MultilineTextField::class,
            MultiSearchSelectField::class,
            MultiSelectField::class,
            RadioField::class,
            SelectField::class,
            SearchSelectField::class,
            TextField::class
        ];


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
     * @param string $class
     */
    public function addClass(string $class) : void
    {
        if (!in_array($class, $this->classes)) {
            $this->classes[] = $class;
        }
    }


    /**
     * @param bool     $check_can_be_added_only_once
     * @param int|null $parent_context
     * @param int|null $parent_id
     *
     * @return string[]
     */
    public function getClasses(bool $check_can_be_added_only_once = false, ?int $parent_context = null, ?int $parent_id = null) : array
    {
        $classes = array_combine(array_map(function (string $class) : string {
            return $class::getType();
        }, $this->classes), $this->classes);

        if ($check_can_be_added_only_once) {
            $classes = array_filter($classes, function (string $class) use ($parent_context, $parent_id) : bool {
                if ($class === GroupField::class) {
                    if (!self::requiredData()->isEnableGroups() || $parent_context === GroupField::PARENT_CONTEXT_FIELD_GROUP) {
                        return false;
                    }
                }

                if ($class::canBeAddedOnlyOnce()) {
                    return empty(self::requiredData()->fields()->getFields($parent_context, $parent_id, [
                        $class::getType()
                    ], false));
                } else {
                    return true;
                }
            });
        }

        ksort($classes);

        return $classes;
    }


    /**
     * @param FieldCtrl $parent
     *
     * @return CreateFieldFormBuilder
     */
    public function newCreateFormBuilderInstance(FieldCtrl $parent) : CreateFieldFormBuilder
    {
        $form = new CreateFieldFormBuilder($parent);

        return $form;
    }


    /**
     * @param FieldCtrl     $parent
     * @param AbstractField $field
     *
     * @return AbstractFieldFormBuilder
     */
    public function newFormBuilderInstance(FieldCtrl $parent, AbstractField $field) : AbstractFieldFormBuilder
    {
        $class = get_class($field) . "FormBuilder";

        $class = substr_replace($class, "\\Form\\", strrpos($class, "\\"), 1);

        $form = new $class($parent, $field);

        return $form;
    }


    /**
     * @param string $type
     *
     * @return AbstractField|null
     */
    public function newInstance(string $type) : ?AbstractField
    {
        $field = null;

        foreach ($this->getClasses() as $type_class => $class) {
            if ($type_class === $type) {
                $field = new $class();
                break;
            }
        }

        return $field;
    }


    /**
     * @param FieldsCtrl $parent
     *
     * @return TableBuilder
     */
    public function newTableBuilderInstance(FieldsCtrl $parent) : TableBuilder
    {
        $table = new TableBuilder($parent);

        return $table;
    }
}
