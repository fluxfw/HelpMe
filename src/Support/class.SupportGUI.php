<?php

namespace srag\Plugins\HelpMe\Support;

use ilHelpMePlugin;
use ilLogLevel;
use ilPropertyFormGUI;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Config\ConfigFormGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use Throwable;

/**
 * Class SupportGUI
 *
 * @package           srag\Plugins\HelpMe\Support
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\HelpMe\Support\SupportGUI: ilUIPluginRouterGUI
 */
class SupportGUI
{

    use DICTrait;
    use HelpMeTrait;
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    const CMD_ADD_SUPPORT = "addSupport";
    const CMD_NEW_SUPPORT = "newSupport";
    const LANG_MODULE = "support";
    /**
     * @var Support
     */
    protected $support;


    /**
     * SupportGUI constructor
     */
    public function __construct()
    {

    }


    /**
     *
     */
    public function executeCommand()/*: void*/
    {
        $this->support = self::helpMe()->support()->factory()->newInstance();

        if (!self::helpMe()->currentUserHasRole()) {
            die();
        }

        self::dic()->ctrl()->saveParameter($this, Repository::GET_PARAM_REF_ID);

        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            case strtolower(ProjectSelectInputGUI::class):
                self::dic()->ctrl()->forwardCommand(self::helpMe()->support()->factory()->newFormInstance($this, $this->support)->extractProjectSelector());
                break;

            case strtolower(IssueTypeSelectInputGUI::class):
                self::dic()->ctrl()->forwardCommand(self::helpMe()->support()->factory()->newFormInstance($this, $this->support)->extractIssueTypeSelector());
                break;

            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_ADD_SUPPORT:
                    case self::CMD_NEW_SUPPORT:
                        $this->{$cmd}();
                        break;

                    default:
                        break;
                }
                break;
        }
    }


    /**
     *
     */
    protected function setTabs()/*: void*/
    {

    }


    /**
     * @param string|null       $message
     * @param ilPropertyFormGUI $form
     */
    protected function show(/*?string*/ $message, ilPropertyFormGUI $form)/*: void*/
    {
        $tpl = self::plugin()->template("helpme_modal.html");

        $tpl->setCurrentBlock("helpme_info");
        $tpl->setVariable("INFO", self::helpMe()->config()->getField(ConfigFormGUI::KEY_INFO));

        if ($message !== null) {
            $tpl->setCurrentBlock("helpme_message");
            $tpl->setVariable("MESSAGE", $message);
        }

        $tpl->setCurrentBlock("helpme_form");
        $tpl->setVariable("FORM", self::output()->getHTML($form));

        self::output()->output($tpl);
    }


    /**
     *
     */
    protected function addSupport()/*: void*/
    {
        $message = null;

        $form = self::helpMe()->support()->factory()->newFormInstance($this, $this->support);

        $this->show($message, $form);
    }


    /**
     *
     */
    protected function newSupport()/*: void*/
    {
        $message = null;

        $form = self::helpMe()->support()->factory()->newFormInstance($this, $this->support);

        if (!$form->storeForm()) {
            $this->show($message, $form);

            return;
        }

        try {
            $recipient = self::helpMe()->support()->recipients()->factory()->newInstance(self::helpMe()->config()->getField(ConfigFormGUI::KEY_RECIPIENT), $this->support);

            $recipient->sendSupportToRecipient();

            if (self::version()->is54()) {
                $message = self::output()->getHTML(self::dic()->ui()->factory()->messageBox()->success(self::plugin()
                    ->translate("sent_success", self::LANG_MODULE)));
                if (self::helpMe()->config()->getField(ConfigFormGUI::KEY_SEND_CONFIRMATION_EMAIL) || self::helpMe()->config()->getField(ConfigFormGUI::KEY_JIRA_CREATE_SERVICE_DESK_REQUEST)) {
                    $message .= self::output()->getHTML(self::dic()->ui()->factory()->messageBox()->info(self::plugin()
                        ->translate("sent_success_confirmation_email", self::LANG_MODULE)));
                }
            } else {
                $message = self::dic()->mainTemplate()->getMessageHTML(self::plugin()
                    ->translate("sent_success", self::LANG_MODULE), "success");
                if (self::helpMe()->config()->getField(ConfigFormGUI::KEY_SEND_CONFIRMATION_EMAIL) || self::helpMe()->config()->getField(ConfigFormGUI::KEY_JIRA_CREATE_SERVICE_DESK_REQUEST)) {
                    $message .= self::dic()->mainTemplate()->getMessageHTML(self::plugin()
                        ->translate("sent_success_confirmation_email", self::LANG_MODULE), "info");
                }
            }

            $form = self::helpMe()->support()->factory()->newSuccessFormInstance($this, $this->support);
        } catch (Throwable $ex) {
            self::dic()->logger()->root()->log($ex->__toString(), ilLogLevel::ERROR);

            if (self::version()->is54()) {
                $message = self::output()->getHTML(self::dic()->ui()->factory()->messageBox()->failure(self::plugin()
                    ->translate("sent_failure", self::LANG_MODULE)));
            } else {
                $message = self::dic()->mainTemplate()->getMessageHTML(self::plugin()
                    ->translate("sent_failure", self::LANG_MODULE), "failure");
            }
        }

        $this->show($message, $form);
    }
}
