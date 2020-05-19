<?php

namespace srag\Plugins\HelpMe\MetaBar;

use ilHelpMePlugin;
use ilHelpMeUIHookGUI;
use ILIAS\GlobalScreen\Scope\MetaBar\Provider\AbstractStaticMetaBarPluginProvider;
use ILIAS\UI\Component\Component;
use ilSession;
use ilUIPluginRouterGUI;
use srag\CustomInputGUIs\HelpMe\MultiSelectSearchNewInputGUI\MultiSelectSearchNewInputGUI;
use srag\CustomInputGUIs\HelpMe\ScreenshotsInputGUI\ScreenshotsInputGUI;
use srag\CustomInputGUIs\HelpMe\TabsInputGUI\TabsInputGUI;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\RequiredData\Field\IssueType\Form\IssueTypeSelectInputGUI;
use srag\Plugins\HelpMe\RequiredData\Field\Project\Form\ProjectSelectInputGUI;
use srag\Plugins\HelpMe\Support\Repository;
use srag\Plugins\HelpMe\Support\SupportGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class MetaBar
 *
 * @package srag\Plugins\HelpMe\MetaBar
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class MetaBar extends AbstractStaticMetaBarPluginProvider
{

    use DICTrait;
    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;


    /**
     * @inheritDoc
     */
    public function getMetaBarItems() : array
    {
        return [
            $this->meta_bar
                ->topLegacyItem($this->if->identifier(ilHelpMePlugin::PLUGIN_ID . "_top"))
                ->withTitle(self::plugin()->translate("support", SupportGUI::LANG_MODULE))
                ->withSymbol(self::dic()->ui()->factory()->symbol()->glyph()->help())
                ->withLegacyContent($this->getSupportChildrenButtons())
                ->withAvailableCallable(function () : bool {
                    return self::plugin()->getPluginObject()->isActive();
                })
                ->withVisibilityCallable(function () : bool {
                    return self::helpMe()->currentUserHasRole();
                })
        ];
    }


    /**
     * @return Component
     */
    protected function getSupportChildrenButtons() : Component
    {
        self::dic()->ctrl()->setParameterByClass(SupportGUI::class, Repository::GET_PARAM_REF_ID, self::helpMe()->support()->getRefId());

        $project_id = ilSession::get(ilHelpMeUIHookGUI::SESSION_PROJECT_URL_KEY);
        self::dic()->ctrl()->setParameterByClass(SupportGUI::class, SupportGUI::GET_PARAM_PROJECT_URL_KEY, $project_id);
        ilSession::clear(ilHelpMeUIHookGUI::SESSION_PROJECT_URL_KEY);

        self::dic()->ctrl()->saveParameterByClass(SupportGUI::class, "lang");

        $buttons = [
            self::dic()->ui()->factory()->button()->bulky(self::dic()->ui()->factory()->symbol()->glyph()->help(),
                self::plugin()->translate("support", SupportGUI::LANG_MODULE),
                self::dic()->ctrl()
                    ->getLinkTargetByClass([
                        ilUIPluginRouterGUI::class,
                        SupportGUI::class
                    ], SupportGUI::CMD_ADD_SUPPORT, "", true))
                ->withAdditionalOnLoadCode(function (string $id) use ($project_id) : string {
                    // Fix some pages may not load Form.js
                    self::dic()->ui()->mainTemplate()->addJavaScript("Services/Form/js/Form.js");

                    $screenshot = new ScreenshotsInputGUI();
                    $screenshot->withPlugin(self::plugin());
                    $screenshot->init();

                    MultiSelectSearchNewInputGUI::init();
                    TabsInputGUI::init();

                    self::dic()->ui()->mainTemplate()->addCss(substr(self::plugin()->directory(), 2) . "/css/HelpMe.css");

                    self::dic()->ui()->mainTemplate()->addJavaScript(substr(self::plugin()->directory(), 2) . "/js/HelpMe.min.js", false);

                    return 'il.HelpMe.BUTTON_ID = ' . json_encode($id) . '
il.HelpMe.MODAL_TEMPLATE = ' . json_encode($this->getModal()) . ';
il.HelpMe.GET_SHOW_TICKETS_OF_PROJECT_URL = ' . json_encode(self::dic()->ctrl()->getLinkTargetByClass([
                            ilUIPluginRouterGUI::class,
                            SupportGUI::class,
                            ProjectSelectInputGUI::class
                        ], ProjectSelectInputGUI::CMD_GET_SHOW_TICKETS_LINK_OF_PROJECT, "", true)) . ';
il.HelpMe.GET_ISSUE_TYPES_OF_PROJECT_URL = ' . json_encode(self::dic()->ctrl()->getLinkTargetByClass([
                            ilUIPluginRouterGUI::class,
                            SupportGUI::class,
                            IssueTypeSelectInputGUI::class
                        ], IssueTypeSelectInputGUI::CMD_GET_ISSUE_TYPES_OF_PROJECT, "", true)) . ';
il.HelpMe.init();
' . $screenshot->getJSOnLoadCode() . '
' . ($project_id !== null ? 'il.HelpMe.autoOpen = true;' : '');
                })
        ];

        if (self::helpMe()->tickets()->isEnabled()) {
            $buttons[] = self::dic()->ui()->factory()->button()->bulky(self::dic()->ui()->factory()->symbol()->glyph()->help(), self::plugin()
                ->translate("show_tickets", SupportGUI::LANG_MODULE), self::helpMe()->tickets()->getLink());
        }

        return self::dic()->ui()->factory()->legacy(self::output()->getHTML($buttons));
    }


    /**
     * @return string
     */
    protected function getModal() : string
    {
        $modal = self::output()->getHTML(self::dic()->ui()->factory()->modal()->roundtrip(self::plugin()
            ->translate("support", SupportGUI::LANG_MODULE), self::dic()->ui()->factory()->legacy("")));

        // HelpMe needs so patches on the new roundtrip modal ui

        // Large modal
        $modal = str_replace('<div class="modal-dialog"', '<div class="modal-dialog modal-lg"', $modal);

        // Buttons will delivered over the form gui
        $modal = str_replace('<div class="modal-footer">', '<div class="modal-footer" style="display:none;">', $modal);

        return $modal;
    }
}
