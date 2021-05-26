<?php

namespace srag\RequiredData\HelpMe\Field\Field\Group\Form;

use srag\RequiredData\HelpMe\Field\Field\Group\GroupField;
use srag\RequiredData\HelpMe\Field\Field\Group\GroupsCtrl;
use srag\RequiredData\HelpMe\Field\FieldCtrl;
use srag\RequiredData\HelpMe\Field\Form\AbstractFieldFormBuilder;

/**
 * Class GroupFieldFormBuilder
 *
 * @package srag\RequiredData\HelpMe\Field\Field\Group\Form
 */
class GroupFieldFormBuilder extends AbstractFieldFormBuilder
{

    /**
     * @var GroupField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(FieldCtrl $parent, GroupField $field)
    {
        parent::__construct($parent, $field);
    }


    /**
     * @inheritDoc
     */
    public function render() : string
    {
        GroupsCtrl::addTabs();

        return parent::render();
    }
}
