<?php

namespace srag\Plugins\HelpMe\Support;

use ilEMailInputGUI;
use ilHelpMePlugin;
use ilHelpMeUIHookGUI;
use ilNonEditableValueGUI;
use ilSelectInputGUI;
use ilSession;
use ilTextAreaInputGUI;
use ilTextInputGUI;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\Items\Items;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\PropertyFormGUI;
use srag\CustomInputGUIs\HelpMe\ScreenshotsInputGUI\ScreenshotsInputGUI;
use srag\Plugins\HelpMe\Config\ConfigFormGUI;
use srag\Plugins\HelpMe\Project\Project;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class SupportFormGUI
 *
 * @package srag\Plugins\HelpMe\Support
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class SupportFormGUI extends PropertyFormGUI
{

    use HelpMeTrait;
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    const LANG_MODULE = SupportGUI::LANG_MODULE;
    /**
     * @var Support
     */
    protected $support;
    /**
     * @var Project|null
     */
    protected $project = null;


    /**
     * SupportFormGUI constructor
     *
     * @param SupportGUI $parent
     * @param Support    $support
     */
    public function __construct(SupportGUI $parent, Support $support)
    {
        $this->support = $support;

        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    protected function getValue(/*string*/ $key)
    {
        switch ($key) {
            case "page_reference":
                return self::helpMe()->support()->getRefLink();

            case "project":
                if ($this->project !== null) {
                    return $this->project->getProjectUrlKey();
                }

                return null;

            case "name":
                if (self::helpMe()->ilias()->users()->getUserId() !== intval(ANONYMOUS_USER_ID)) {
                    return self::dic()->user()->getFullname();
                }

                return null;

            case "login":
                return self::dic()->user()->getLogin();

            case "email":
                if (self::helpMe()->ilias()->users()->getUserId() !== intval(ANONYMOUS_USER_ID)) {
                    return self::dic()->user()->getEmail();
                }

                return null;

            case "system_infos":
                return self::helpMe()->support()->getBrowserInfos();

            default:
                return Items::getter($this->support, $key);
        }
    }


    /**
     * @inheritDoc
     */
    protected final function initAction()/*: void*/
    {
        $this->setFormAction(self::dic()->ctrl()->getFormAction($this->parent, "", "", true));
    }


    /**
     * @inheritDoc
     */
    protected function initCommands()/*: void*/
    {
        $this->addCommandButton(SupportGUI::CMD_NEW_SUPPORT, $this->txt("submit"), "helpme_submit");

        $this->addCommandButton("", $this->txt("cancel"), "helpme_cancel");

        $this->setShowTopButtons(false);
    }


    /**
     * @inheritDoc
     */
    protected function initFields()/*: void*/
    {
        // Preselect project (Support link)
        $project_url_key = ilSession::get(ilHelpMeUIHookGUI::SESSION_PROJECT_URL_KEY);
        if (!empty($project_url_key)) {
            ilSession::clear(ilHelpMeUIHookGUI::SESSION_PROJECT_URL_KEY);

            $this->project = self::helpMe()->projects()->getProjectByUrlKey($project_url_key);
        }

        $this->fields = (self::helpMe()->support()->getRefId() !== null ? [
                "page_reference" => [
                    self::PROPERTY_CLASS => ilNonEditableValueGUI::class
                ],
            ] : []) + [
                "project"         => [
                    self::PROPERTY_CLASS    => ProjectSelectInputGUI::class,
                    self::PROPERTY_REQUIRED => true,
                    self::PROPERTY_OPTIONS  => [
                            "" => "&lt;" . $this->txt("please_select") . "&gt;"
                        ] + self::helpMe()->projects()->getProjectsOptions()
                ],
                "issue_type"      => [
                    self::PROPERTY_CLASS    => IssueTypeSelectInputGUI::class,
                    self::PROPERTY_REQUIRED => true,
                    self::PROPERTY_OPTIONS  => [],
                    self::PROPERTY_DISABLED => true
                ],
                "title"           => [
                    self::PROPERTY_CLASS    => ilTextInputGUI::class,
                    self::PROPERTY_REQUIRED => true
                ],
                "name"            => [
                    self::PROPERTY_CLASS    => (self::helpMe()->ilias()->users()->getUserId()
                    === intval(ANONYMOUS_USER_ID) ? ilTextInputGUI::class : ilNonEditableValueGUI::class),
                    self::PROPERTY_REQUIRED => true
                ],
                "login"           => [
                    self::PROPERTY_CLASS    => ilNonEditableValueGUI::class,
                    self::PROPERTY_REQUIRED => true
                ],
                "email"           => [
                    self::PROPERTY_CLASS    => ilEMailInputGUI::class,
                    self::PROPERTY_REQUIRED => true
                ],
                "phone"           => [
                    self::PROPERTY_CLASS    => ilTextInputGUI::class,
                    self::PROPERTY_REQUIRED => false
                ],
                "priority"        => [
                    self::PROPERTY_CLASS    => ilSelectInputGUI::class,
                    self::PROPERTY_REQUIRED => true,
                    self::PROPERTY_OPTIONS  => [
                            "" => "&lt;" . $this->txt("please_select") . "&gt;"
                        ] + self::helpMe()->config()->getField(ConfigFormGUI::KEY_PRIORITIES)
                ],
                "description"     => [
                    self::PROPERTY_CLASS    => ilTextAreaInputGUI::class,
                    self::PROPERTY_REQUIRED => true
                ],
                "reproduce_steps" => [
                    self::PROPERTY_CLASS    => ilTextAreaInputGUI::class,
                    self::PROPERTY_REQUIRED => false
                ],
                "system_infos"    => [
                    self::PROPERTY_CLASS    => ilNonEditableValueGUI::class,
                    self::PROPERTY_REQUIRED => true
                ],
                "screenshots"     => [
                    self::PROPERTY_CLASS    => ScreenshotsInputGUI::class,
                    self::PROPERTY_REQUIRED => false,
                    "withPlugin"            => self::plugin()
                ]
            ];
    }


    /**
     * @inheritDoc
     */
    protected final function initId()/*: void*/
    {
        $this->setId("helpme_form");
    }


    /**
     * @inheritDoc
     */
    protected final function initTitle()/*: void*/
    {
    }


    /**
     * @inheritDoc
     */
    protected function storeValue(/*string*/ $key, $value)/*: void*/
    {
        switch ($key) {
            case "page_reference":
                Items::setter($this->support, $key, self::helpMe()->support()->getRefLink());
                break;

            case "project":
                Items::setter($this->support, $key, $this->project);
                break;

            case "issue_type":
                Items::setter($this->support, $key, $value);
                Items::setter($this->support, "fix_version", self::helpMe()->projects()->getFixVersionForIssueType($this->project, $this->support->getIssueType()));
                break;

            case "name":
                if (self::helpMe()->ilias()->users()->getUserId() === intval(ANONYMOUS_USER_ID)) {
                    Items::setter($this->support, $key, $value);
                } else {
                    Items::setter($this->support, $key, self::dic()->user()->getFullname());
                }
                break;

            case "login":
                Items::setter($this->support, $key, self::dic()->user()->getLogin());
                break;

            case "priority":
                $configPriorities = self::helpMe()->config()->getField(ConfigFormGUI::KEY_PRIORITIES);

                $priority_id = intval($value);

                foreach ($configPriorities as $id => $priority) {
                    if ($id === $priority_id) {
                        Items::setter($this->support, $key, $priority);
                        break;
                    }
                }
                break;

            case "system_infos":
                Items::setter($this->support, $key, self::helpMe()->support()->getBrowserInfos());
                break;

            case "screenshots":
                foreach ($value as $screenshot) {
                    $this->support->addScreenshot($screenshot);
                }
                break;

            default:
                Items::setter($this->support, $key, $value);
                break;
        }
    }


    /**
     * @inheritDoc
     */
    public function storeForm() : bool
    {
        $time = time();
        $this->support->setTime($time);

        return parent::storeForm();
    }


    /**
     * @return Project|null
     */
    public function getProject()/*: ?Project*/
    {
        return $this->project;
    }


    /**
     * @param Project|null $project
     */
    public function setProject(/*?*/ Project $project = null)/*: void*/
    {
        $this->project = $project;
    }


    /**
     * @return ProjectSelectInputGUI
     */
    public function extractProjectSelector() : ProjectSelectInputGUI
    {
        return $this->getItemByPostVar("project");
    }


    /**
     * @return IssueTypeSelectInputGUI
     */
    public function extractIssueTypeSelector() : IssueTypeSelectInputGUI
    {
        return $this->getItemByPostVar("issue_type");
    }
}
