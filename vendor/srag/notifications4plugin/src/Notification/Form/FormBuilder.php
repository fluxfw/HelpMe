<?php

namespace srag\Notifications4Plugin\HelpMe\Notification\Form;

use ILIAS\UI\Component\Input\Field\Group;
use ILIAS\UI\Component\Input\Field\Radio;
use ilNonEditableValueGUI;
use ilTextInputGUI;
use srag\CustomInputGUIs\HelpMe\FormBuilder\AbstractFormBuilder;
use srag\CustomInputGUIs\HelpMe\InputGUIWrapperUIInputComponent\InputGUIWrapperUIInputComponent;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\Items\Items;
use srag\CustomInputGUIs\HelpMe\TabsInputGUI\MultilangualTabsInputGUI;
use srag\CustomInputGUIs\HelpMe\TabsInputGUI\TabsInputGUI;
use srag\CustomInputGUIs\HelpMe\TextAreaInputGUI\TextAreaInputGUI;
use srag\Notifications4Plugin\HelpMe\Notification\NotificationCtrl;
use srag\Notifications4Plugin\HelpMe\Notification\NotificationInterface;
use srag\Notifications4Plugin\HelpMe\Notification\NotificationsCtrl;
use srag\Notifications4Plugin\HelpMe\Parser\Parser;
use srag\Notifications4Plugin\HelpMe\Utils\Notifications4PluginTrait;

/**
 * Class FormBuilder
 *
 * @package srag\Notifications4Plugin\HelpMe\Notification\Form
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class FormBuilder extends AbstractFormBuilder
{

    use Notifications4PluginTrait;

    /**
     * @var NotificationInterface
     */
    protected $notification;


    /**
     * @inheritDoc
     *
     * @param NotificationCtrl      $parent
     * @param NotificationInterface $notification
     */
    public function __construct(NotificationCtrl $parent, NotificationInterface $notification)
    {
        $this->notification = $notification;

        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    protected function getButtons() : array
    {
        $buttons = [];

        if (!empty($this->notification->getId())) {
            $buttons[NotificationCtrl::CMD_UPDATE_NOTIFICATION] = self::notifications4plugin()->getPlugin()->translate("save", NotificationsCtrl::LANG_MODULE);
        } else {
            $buttons[NotificationCtrl::CMD_CREATE_NOTIFICATION] = self::notifications4plugin()->getPlugin()->translate("add", NotificationsCtrl::LANG_MODULE);
        }

        $buttons[NotificationCtrl::CMD_BACK] = self::notifications4plugin()->getPlugin()->translate("cancel", NotificationsCtrl::LANG_MODULE);

        return $buttons;
    }


    /**
     * @inheritDoc
     */
    protected function getData() : array
    {
        $data = [];

        foreach (array_keys($this->getFields()) as $key) {
            switch ($key) {
                case "parser":
                    $data[$key] = [
                        "value"        => Items::getter($this->notification, $key),
                        "group_values" => $this->notification->getParserOptions()
                    ];
                    break;

                default:
                    $data[$key] = Items::getter($this->notification, $key);
                    break;
            }
        }

        return $data;
    }


    /**
     * @inheritDoc
     */
    protected function getFields() : array
    {
        $fields = [];

        if (!empty($this->notification->getId())) {
            $fields += [
                "id"   => new InputGUIWrapperUIInputComponent(new ilNonEditableValueGUI(self::notifications4plugin()->getPlugin()->translate("id", NotificationsCtrl::LANG_MODULE))),
                "name" => (new InputGUIWrapperUIInputComponent(new ilNonEditableValueGUI(self::notifications4plugin()
                    ->getPlugin()
                    ->translate("name", NotificationsCtrl::LANG_MODULE))))->withByline(self::notifications4plugin()->getPlugin()->translate("name_info", NotificationsCtrl::LANG_MODULE))
            ];
        } else {
            $fields += [
                "name" => self::dic()
                    ->ui()
                    ->factory()
                    ->input()
                    ->field()
                    ->text(self::notifications4plugin()->getPlugin()->translate("name", NotificationsCtrl::LANG_MODULE))
                    ->withByline(self::notifications4plugin()->getPlugin()->translate("name_info", NotificationsCtrl::LANG_MODULE))
                    ->withRequired(true)
            ];
        }

        if (self::version()->is6()) {
            $parser = self::dic()->ui()->factory()->input()->field()->switchableGroup(array_map(function (Parser $parser) : Group {
                return self::dic()->ui()->factory()->input()->field()->group($parser->getOptionsFields(), $parser->getName() . "<br>" . self::output()->getHTML(self::dic()->ui()->factory()->link()
                        ->standard($parser->getDocLink(), $parser->getDocLink())->withOpenInNewViewport(true)))->withByline(self::output()->getHTML(self::dic()->ui()->factory()->link()
                    ->standard($parser->getDocLink(), $parser->getDocLink())->withOpenInNewViewport(true))); // TODO `withByline` not work in ILIAS 6 group (radio), so temporary in label
            }, self::notifications4plugin()->parser()->getPossibleParsers()), self::notifications4plugin()->getPlugin()->translate("parser", NotificationsCtrl::LANG_MODULE))->withRequired(true);
        } else {
            $parser = array_reduce(self::notifications4plugin()->parser()->getPossibleParsers(), function (Radio $radio, Parser $parser) : Radio {
                $radio = $radio->withOption(get_class($parser), $parser->getName(), self::output()->getHTML(self::dic()->ui()->factory()->link()
                    ->standard($parser->getDocLink(), $parser->getDocLink())->withOpenInNewViewport(true)), $parser->getOptionsFields());

                return $radio;
            }, self::dic()->ui()->factory()->input()->field()->radio(self::notifications4plugin()->getPlugin()->translate("parser", NotificationsCtrl::LANG_MODULE))->withRequired(true));
        }

        $fields += [
            "title"       => self::dic()->ui()->factory()->input()->field()->text(self::notifications4plugin()->getPlugin()->translate("title", NotificationsCtrl::LANG_MODULE))->withRequired(true),
            "description" => self::dic()->ui()->factory()->input()->field()->textarea(self::notifications4plugin()->getPlugin()->translate("description", NotificationsCtrl::LANG_MODULE)),
            "parser"      => $parser,
            "subjects"    => (new InputGUIWrapperUIInputComponent(new TabsInputGUI(self::notifications4plugin()
                ->getPlugin()
                ->translate("subject", NotificationsCtrl::LANG_MODULE))))->withRequired(true),
            "texts"       => (new InputGUIWrapperUIInputComponent(new TabsInputGUI(self::notifications4plugin()->getPlugin()->translate("text", NotificationsCtrl::LANG_MODULE))))->withRequired(true)
        ];
        MultilangualTabsInputGUI::generateLegacy($fields["subjects"]->getInput(), [
            new ilTextInputGUI(self::notifications4plugin()->getPlugin()->translate("subject", NotificationsCtrl::LANG_MODULE), "subject")
        ], true);
        $input = new TextAreaInputGUI(self::notifications4plugin()->getPlugin()->translate("text", NotificationsCtrl::LANG_MODULE), "text");
        $input->setRows(10);
        MultilangualTabsInputGUI::generateLegacy($fields["texts"]->getInput(), [
            $input
        ], true);

        return $fields;
    }


    /**
     * @inheritDoc
     */
    protected function getTitle() : string
    {
        if (!empty($this->notification->getId())) {
            return self::notifications4plugin()->getPlugin()->translate("edit_notification", NotificationsCtrl::LANG_MODULE);
        } else {
            return self::notifications4plugin()->getPlugin()->translate("add_notification", NotificationsCtrl::LANG_MODULE);
        }
    }


    /**
     * @inheritDoc
     */
    protected function storeData(array $data) : void
    {
        foreach (array_keys($this->getFields()) as $key) {
            switch ($key) {
                case "id" :
                    break;

                case "name" :
                    if (empty($this->notification->getId())) {
                        Items::setter($this->notification, $key, $data[$key]);
                    }
                    break;

                case "parser":
                    if (self::version()->is6()) {
                        Items::setter($this->notification, $key, $data[$key][0]);

                        foreach (array_keys($this->notification->getParserOptions()) as $parser_option_key) {
                            $this->notification->setParserOption($parser_option_key, $data[$key][1][$parser_option_key]);
                        }
                    } else {
                        Items::setter($this->notification, $key, $data[$key]["value"]);

                        foreach (array_keys($this->notification->getParserOptions()) as $parser_option_key) {
                            $this->notification->setParserOption($parser_option_key, $data[$key]["group_values"][$parser_option_key]);
                        }
                    }
                    break;

                default:
                    Items::setter($this->notification, $key, $data[$key]);
                    break;
            }
        }

        self::notifications4plugin()->notifications()->storeNotification($this->notification);
    }


    /**
     * @inheritDoc
     */
    public function render() : string
    {
        $this->messages[] = self::dic()->ui()->factory()->messageBox()->info(self::output()->getHTML([
            htmlspecialchars(self::notifications4plugin()->getPlugin()->translate("placeholder_types_info", NotificationsCtrl::LANG_MODULE)),
            "<br><br>",
            self::dic()->ui()->factory()->listing()->descriptive(self::notifications4plugin()->getPlaceholderTypes())
        ]));

        return parent::render();
    }
}
