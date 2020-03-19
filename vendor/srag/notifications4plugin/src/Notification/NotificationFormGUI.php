<?php

namespace srag\Notifications4Plugin\HelpMe\Notification;

use ilNonEditableValueGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilTextAreaInputGUI;
use ilTextInputGUI;
use ilUtil;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\Items\Items;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\PropertyFormGUI;
use srag\CustomInputGUIs\HelpMe\TabsInputGUI\MultilangualTabsInputGUI;
use srag\CustomInputGUIs\HelpMe\TabsInputGUI\TabsInputGUI;
use srag\CustomInputGUIs\HelpMe\TextAreaInputGUI\TextAreaInputGUI;
use srag\Notifications4Plugin\HelpMe\Parser\Parser;
use srag\Notifications4Plugin\HelpMe\Utils\Notifications4PluginTrait;

/**
 * Class NotificationFormGUI
 *
 * @package srag\Notifications4Plugin\HelpMe\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
class NotificationFormGUI extends PropertyFormGUI
{

    use Notifications4PluginTrait;
    const LANG_MODULE = NotificationsCtrl::LANG_MODULE;
    /**
     * @var NotificationInterface
     */
    protected $notification;


    /**
     * NotificationFormGUI constructor
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
    protected function getValue(/*string*/ $key)/* : void*/
    {
        switch (true) {
            case (strpos($key, "parser_option_") === 0):
                $parser_option = substr($key, strlen("parser_option_"));

                return $this->notification->getParserOption($parser_option);

            default:
                return Items::getter($this->notification, $key);
        }
    }


    /**
     * @inheritDoc
     */
    protected function initCommands()/* : void*/
    {
        if (!empty($this->notification->getId())) {
            $this->addCommandButton(NotificationCtrl::CMD_UPDATE_NOTIFICATION, $this->txt("save"));
        } else {
            $this->addCommandButton(NotificationCtrl::CMD_CREATE_NOTIFICATION, $this->txt("add"));
        }

        $this->addCommandButton(NotificationCtrl::CMD_BACK, $this->txt("cancel"));
    }


    /**
     * @inheritDoc
     */
    protected function initId()/* : void*/
    {

    }


    /**
     * @inheritDoc
     */
    protected function initFields()/* : void*/
    {
        ilUtil::sendInfo(self::output()->getHTML([
            htmlspecialchars(self::notifications4plugin()->getPlugin()->translate("placeholder_types_info", NotificationsCtrl::LANG_MODULE)),
            "<br><br>",
            self::dic()->ui()->factory()->listing()->descriptive(self::notifications4plugin()->getPlaceholderTypes())
        ]));

        $this->fields = (!empty($this->notification->getId()) ? [
                "id" => [
                    self::PROPERTY_CLASS    => ilNonEditableValueGUI::class,
                    self::PROPERTY_REQUIRED => true
                ]
            ] : []) + [
                "name"        => [
                    self::PROPERTY_CLASS    => (empty($this->notification->getId()) ? ilTextInputGUI::class : ilNonEditableValueGUI::class),
                    self::PROPERTY_REQUIRED => true
                ],
                "title"       => [
                    self::PROPERTY_CLASS    => ilTextInputGUI::class,
                    self::PROPERTY_REQUIRED => true
                ],
                "description" => [
                    self::PROPERTY_CLASS    => ilTextAreaInputGUI::class,
                    self::PROPERTY_REQUIRED => false
                ],
                "parser"      => [
                    self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                    self::PROPERTY_REQUIRED => true,
                    self::PROPERTY_SUBITEMS => array_map(function (Parser $parser) : array {
                        $parser_options = [];

                        foreach ($parser->getOptionsFields() as $key => $field) {
                            $parser_options["parser_option_" . $key] = $field;
                        }

                        return [
                            self::PROPERTY_CLASS    => ilRadioOption::class,
                            self::PROPERTY_SUBITEMS => $parser_options,
                            "setTitle"              => $parser->getName(),
                            "setInfo"               => self::output()->getHTML(self::dic()->ui()->factory()->link()
                                ->standard($parser->getDocLink(), $parser->getDocLink())->withOpenInNewViewport(true))
                        ];
                    }, self::notifications4plugin()->parser()->getPossibleParsers())
                ],
                "subjects"    => [
                    self::PROPERTY_CLASS    => TabsInputGUI::class,
                    self::PROPERTY_REQUIRED => true,
                    self::PROPERTY_SUBITEMS => MultilangualTabsInputGUI::generate([
                        "subject" => [
                            self::PROPERTY_CLASS => ilTextInputGUI::class
                        ]
                    ], true),
                    "setTitle"              => $this->txt("subject")
                ],
                "texts"       => [
                    self::PROPERTY_CLASS    => TabsInputGUI::class,
                    self::PROPERTY_REQUIRED => true,
                    self::PROPERTY_SUBITEMS => MultilangualTabsInputGUI::generate([
                        "text" => [
                            self::PROPERTY_CLASS => TextAreaInputGUI::class,
                            "setRows"            => 10
                        ]
                    ], true),
                    "setTitle"              => $this->txt("text")
                ]
            ];
    }


    /**
     * @inheritDoc
     */
    protected function initTitle()/* : void*/
    {
        $this->setTitle($this->txt(!empty($this->notification->getId()) ? "edit_notification" : "add_notification"));
    }


    /**
     * @inheritDoc
     */
    protected function storeValue(/*string*/ $key, $value)/* : void*/
    {
        switch (true) {
            case ($key === "id"):
                break;

            case ($key === "name"):
                if (empty($this->notification->getId())) {
                    Items::setter($this->notification, $key, $value);
                }
                break;

            case (strpos($key, "parser_option_") === 0):
                $parser_option = substr($key, strlen("parser_option_"));

                $this->notification->setParserOption($parser_option, $value);
                break;

            default:
                Items::setter($this->notification, $key, $value);
                break;
        }
    }


    /**
     * @inheritDoc
     */
    public function storeForm() : bool
    {
        if (!parent::storeForm()) {
            return false;
        }

        self::notifications4plugin()->notifications()->storeNotification($this->notification);

        return true;
    }


    /**
     * @inheritDoc
     */
    public function txt(/*string*/ $key,/*?string*/ $default = null) : string
    {
        if ($default !== null) {
            return self::notifications4plugin()->getPlugin()->translate($key, self::LANG_MODULE, [], true, "", $default);
        } else {
            return self::notifications4plugin()->getPlugin()->translate($key, self::LANG_MODULE);
        }
    }
}
