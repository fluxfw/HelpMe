<?php

namespace srag\CustomInputGUIs\HelpMe\MultiSelectSearchNewInputGUI;

use ilMultiSelectInputGUI;
use ilTableFilterItem;
use ilTemplate;
use ilToolbarItem;
use ilUtil;
use srag\DIC\HelpMe\DICTrait;

/**
 * Class MultiSelectSearchNewInputGUI
 *
 * @package srag\CustomInputGUIs\HelpMe\MultiSelectSearchNewInputGUI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class MultiSelectSearchNewInputGUI extends ilMultiSelectInputGUI implements ilTableFilterItem, ilToolbarItem
{

    use DICTrait;


    /**
     * MultiSelectSearchNewInputGUI constructor
     *
     * @param string $title
     * @param string $post_var
     */
    public function __construct(string $title = "", string $post_var = "")
    {
        parent::__construct($title, $post_var);
    }


    /**
     * @inheritDoc
     */
    public function checkInput() : bool
    {
        if ($this->getRequired() && empty($this->getValue())) {
            $this->setAlert(self::dic()->language()->txt("msg_input_is_required"));

            return false;
        }

        if ($this->getLimitCount() !== null && count($this->getValue()) > $this->getLimitCount()) {
            $this->setAlert(self::dic()->language()->txt("form_input_not_valid"));

            return false;
        }

        return true;
    }


    /**
     * @var string|null
     */
    protected $ajax_link = null;
    /**
     * @var int|null
     */
    protected $limit_count = null;
    /**
     * @var int|null
     */
    protected $minimum_input_length = null;


    /**
     * @return string|null
     */
    public function getAjaxLink()/*: ?string*/
    {
        return $this->ajax_link;
    }


    /**
     * @return int|null
     */
    public function getLimitCount()/* : ?int*/
    {
        return $this->limit_count;
    }


    /**
     * @return int
     */
    public function getMinimumInputLength() : int
    {
        if ($this->minimum_input_length !== null) {
            return $this->minimum_input_length;
        } else {
            return (!empty($this->getAjaxLink()) ? 1 : 0);
        }
    }


    /**
     * @inheritDoc
     */
    public function getTableFilterHTML() : string
    {
        return $this->render();
    }


    /**
     * @inheritDoc
     */
    public function getToolbarHTML() : string
    {
        return $this->render();
    }


    /**
     * @inheritDoc
     */
    public function render() : string
    {
        $dir = __DIR__;
        $dir = "./" . substr($dir, strpos($dir, "/Customizing/") + 1);
        self::dic()->mainTemplate()->addJavaScript($dir . "/../../node_modules/select2/dist/js/select2.full.min.js");
        self::dic()->mainTemplate()->addJavaScript($dir . "/../../node_modules/select2/dist/js/i18n/" . self::dic()->user()->getCurrentLanguage()
            . ".js");
        self::dic()->mainTemplate()->addCss($dir . "/../../node_modules/select2/dist/css/select2.min.css");

        $tpl = new ilTemplate(__DIR__ . "/templates/multiple_select_new_input_gui.html", true, true);

        $tpl->setVariable("ID", $this->getFieldId());

        $tpl->setVariable("POST_VAR", $this->getPostVar());

        $options = [
            "maximumSelectionLength" => $this->getLimitCount(),
            "minimumInputLength"     => $this->getMinimumInputLength()
        ];
        if (!empty($this->getAjaxLink())) {
            $options["ajax"] = [
                "url" => $this->getAjaxLink()
            ];
        }

        $tpl->setVariable("OPTIONS", json_encode($options));

        if (!empty($this->getOptions())) {

            $tpl->setCurrentBlock("option");

            foreach ($this->getOptions() as $option_value => $option_text) {
                $selected = in_array($option_value, $this->getValue());

                if (!empty($this->getAjaxLink()) && !$selected) {
                    continue;
                }

                if ($selected) {
                    $tpl->setVariable("SELECTED", "selected");
                }

                $tpl->setVariable("VAL", ilUtil::prepareFormOutput($option_value));
                $tpl->setVariable("TEXT", $option_text);

                $tpl->parseCurrentBlock();
            }
        }

        return self::output()->getHTML($tpl);
    }


    /**
     * @param string|null $ajax_link
     */
    public function setAjaxLink(/*?*/ string $ajax_link = null)/*: void*/
    {
        $this->ajax_link = $ajax_link;
    }


    /**
     * @param int|null $limit_count
     */
    public function setLimitCount(/*?*/ int $limit_count = null)/* : void*/
    {
        $this->limit_count = $limit_count;
    }


    /**
     * @param int|null $minimum_input_length
     */
    public function setMinimumInputLength(/*?*/ int $minimum_input_length = null)/*: void*/
    {
        $this->minimum_input_length = $minimum_input_length;
    }
}
