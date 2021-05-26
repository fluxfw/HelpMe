<?php

namespace srag\RequiredData\HelpMe\Field\Field\Group;

require_once __DIR__ . "/../../../../../../autoload.php";

use srag\RequiredData\HelpMe\Field\FieldCtrl;

/**
 * Class GroupCtrl
 *
 * @package           srag\RequiredData\HelpMe\Field\Field\Group
 *
 * @ilCtrl_isCalledBy srag\RequiredData\HelpMe\Field\Field\Group\GroupCtrl: srag\RequiredData\HelpMe\Field\Field\Group\GroupsCtrl
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
