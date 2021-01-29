<?php

namespace srag\RequiredData\HelpMe\Field\Field\Group;

require_once __DIR__ . "/../../../../../../autoload.php";

use srag\RequiredData\HelpMe\Field\FieldCtrl;

/**
 * Class GroupCtrl
 *
 * @package           srag\RequiredData\HelpMe\Field\Field\Group
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\RequiredData\HelpMe\Field\Field\Group\GroupCtrl: srag\RequiredData\HelpMe\Field\Field\Group\GroupsCtrl
 * @ilCtrl_isCalledBy srag\RequiredData\HelpMe\Field\Field\StaticMultiSearchSelect\SMSSAjaxAutoCompleteCtrl: srag\RequiredData\HelpMe\Field\Field\Group\GroupCtrl
 */
class GroupCtrl extends FieldCtrl
{

    /**
     * @inheritDoc
     */
    protected function ungroup() : void
    {
        die();
    }
}
