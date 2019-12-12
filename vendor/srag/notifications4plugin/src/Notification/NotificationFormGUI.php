<?php

namespace srag\Notifications4Plugin\HelpMe\Notification;

use ilNonEditableValueGUI;
use ilSelectInputGUI;
use ilTextAreaInputGUI;
use ilTextInputGUI;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\ObjectPropertyFormGUI;
use srag\CustomInputGUIs\HelpMe\TabsInputGUI\MultilangualTabsInputGUI;
use srag\CustomInputGUIs\HelpMe\TabsInputGUI\TabsInputGUI;
use srag\CustomInputGUIs\HelpMe\TextAreaInputGUI\TextAreaInputGUI;
use srag\Notifications4Plugin\HelpMe\Parser\twigParser;
use srag\Notifications4Plugin\HelpMe\Utils\Notifications4PluginTrait;

/**
 * Class NotificationFormGUI
 *
 * @package srag\Notifications4Plugin\HelpMe\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
class NotificationFormGUI extends ObjectPropertyFormGUI
{

    use Notifications4PluginTrait;
    const LANG_MODULE = NotificationsCtrl::LANG_MODULE;
    /**
     * @var NotificationInterface
     */
    protected $object;


    /**
     * NotificationFormGUI constructor
     *
     * @param NotificationCtrl      $parent
     * @param NotificationInterface $object
     */
    public function __construct(NotificationCtrl $parent, NotificationInterface $object)
    {
        parent::__construct($parent, $object, false);
    }


    /**
     * @inheritdoc
     */
    protected function getValue(/*string*/ $key)/*: void*/
    {
        switch ($key) {
            default:
                return parent::getValue($key);
        }
    }


    /**
     * @inheritdoc
     */
    protected function initCommands()/*: void*/
    {
        if (!empty($this->object->getId())) {
            $this->addCommandButton(NotificationCtrl::CMD_UPDATE_NOTIFICATION, $this->txt("save"));
        } else {
            $this->addCommandButton(NotificationCtrl::CMD_CREATE_NOTIFICATION, $this->txt("add"));
        }

        $this->addCommandButton(NotificationCtrl::CMD_BACK, $this->txt("cancel"));
    }


    /**
     * @inheritdoc
     */
    protected function initId()/*: void*/
    {

    }


    /**
     * @inheritdoc
     */
    protected function initFields()/*: void*/
    {
        $this->fields = (!empty($this->object->getId()) ? [
                "id" => [
                    self::PROPERTY_CLASS    => ilNonEditableValueGUI::class,
                    self::PROPERTY_REQUIRED => true
                ]
            ] : []) + [
                "name"        => [
                    self::PROPERTY_CLASS    => (empty($this->object->getId()) ? ilTextInputGUI::class : ilNonEditableValueGUI::class),
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
                    self::PROPERTY_CLASS    => ilSelectInputGUI::class,
                    self::PROPERTY_REQUIRED => true,
                    self::PROPERTY_OPTIONS  => self::notifications4plugin()->parser()->getPossibleParsers(),
                    "setInfo"               => twigParser::NAME . ": " . self::output()->getHTML(self::dic()->ui()->factory()->link()
                            ->standard(twigParser::DOC_LINK, twigParser::DOC_LINK)->withOpenInNewViewport(true))
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
     * @inheritdoc
     */
    protected function initTitle()/*: void*/
    {
        $this->setTitle($this->txt(!empty($this->object->getId()) ? "edit_notification" : "add_notification"));
    }


    /**
     * @inheritdoc
     */
    protected function storeValue(/*string*/ $key, $value)/*: void*/
    {
        switch ($key) {
            case "id":
                break;

            case "name":
                if (empty($this->object->getId())) {
                    parent::storeValue($key, $value);
                }
                break;

            default:
                parent::storeValue($key, $value);
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

        self::notifications4plugin()->notifications()->storeNotification($this->object);

        return true;
    }


    /**
     * @inheritdoc
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
