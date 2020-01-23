<?php

namespace srag\CustomInputGUIs\HelpMe\MultiLineNewInputGUI;

use ilFormPropertyGUI;
use ilTableFilterItem;
use ilTemplate;
use ilToolbarItem;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\Items\Items;
use srag\DIC\HelpMe\DICTrait;

/**
 * Class MultiLineNewInputGUI
 *
 * @package srag\CustomInputGUIs\HelpMe\MultiLineNewInputGUI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class MultiLineNewInputGUI extends ilFormPropertyGUI implements ilTableFilterItem, ilToolbarItem
{

    use DICTrait;
    const SHOW_INPUT_LABEL_NONE = 1;
    const SHOW_INPUT_LABEL_ONCE = 2;
    const SHOW_INPUT_LABEL_ALWAYS = 3;
    /**
     * @var bool
     */
    protected static $init = false;


    /**
     *
     */
    public static function init()/*: void*/
    {
        if (self::$init === false) {
            self::$init = true;

            $dir = __DIR__;
            $dir = "./" . substr($dir, strpos($dir, "/Customizing/") + 1);

            self::dic()->mainTemplate()->addCss($dir . "/css/multi_line_new_input_gui.css");

            self::dic()->mainTemplate()->addJavaScript($dir . "/js/multi_line_new_input_gui.min.js");
        }
    }


    /**
     * @var ilFormPropertyGUI[]
     */
    protected $inputs = [];
    /**
     * @var ilFormPropertyGUI[]|null
     */
    protected $inputs_generated = null;
    /**
     * @var int
     */
    protected $show_input_label = self::SHOW_INPUT_LABEL_ONCE;
    /**
     * @var bool
     */
    protected $show_sort = true;
    /**
     * @var array
     */
    protected $value = [];


    /**
     * MultiLineNewInputGUI constructor
     *
     * @param string $title
     * @param string $post_var
     */
    public function __construct(string $title = "", string $post_var = "")
    {
        parent::__construct($title, $post_var);

        self::init();
    }


    /**
     * @param ilFormPropertyGUI $input
     */
    public function addInput(ilFormPropertyGUI $input)/*: void*/
    {
        $this->inputs[] = $input;
        $this->inputs_generated = null;
    }


    /**
     * @return bool
     */
    public function checkInput() : bool
    {
        $ok = true;

        foreach ($this->getInputs($this->required) as $i => $inputs) {
            foreach ($inputs as $org_post_var => $input) {
                $b_value = $_POST[$input->getPostVar()];

                $_POST[$input->getPostVar()] = $_POST[$this->getPostVar()][$i][$org_post_var];

                /*if ($this->getRequired()) {
                   $input->setRequired(true);
               }*/

                if (!$input->checkInput()) {
                    $ok = false;
                }

                $_POST[$input->getPostVar()] = $b_value;
            }
        }

        if ($ok) {
            return true;
        } else {
            //$this->setAlert(self::dic()->language()->txt("form_input_not_valid"));

            return false;
        }
    }


    /**
     * @param bool $need_one_line_at_least
     *
     * @return ilFormPropertyGUI[][]
     */
    public function getInputs(bool $need_one_line_at_least = true) : array
    {
        if ($this->inputs_generated === null) {
            $this->inputs_generated = [];

            foreach (array_values($this->getValue($need_one_line_at_least)) as $i => $value) {
                $inputs = [];

                foreach ($this->inputs as $input) {
                    $input = clone $input;

                    $org_post_var = $input->getPostVar();

                    Items::setValueToItem($input, $value[$org_post_var]);

                    $post_var = $this->getPostVar() . "[" . $i . "][";
                    if (strpos($org_post_var, "[") !== false) {
                        $post_var .= strstr($input->getPostVar(), "[", true) . "][" . strstr($org_post_var, "[");
                    } else {
                        $post_var .= $org_post_var . "]";
                    }
                    $input->setPostVar($post_var);

                    $inputs[$org_post_var] = $input;
                }

                $this->inputs_generated[] = $inputs;
            }
        }

        return $this->inputs_generated;
    }


    /**
     * @return int
     */
    public function getShowInputLabel() : int
    {
        return $this->show_input_label;
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
     * @param bool $need_one_line_at_least
     *
     * @return array
     */
    public function getValue(bool $need_one_line_at_least = false) : array
    {
        $values = $this->value;

        if ($need_one_line_at_least && empty($values)) {
            $values = [[]];
        }

        return $values;
    }


    /**
     * @param ilTemplate $tpl
     */
    public function insert(ilTemplate $tpl) /*: void*/
    {
        $html = $this->render();

        $tpl->setCurrentBlock("prop_generic");
        $tpl->setVariable("PROP_GENERIC", $html);
        $tpl->parseCurrentBlock();
    }


    /**
     * @return bool
     */
    public function isShowSort() : bool
    {
        return $this->show_sort;
    }


    /**
     * @return string
     */
    public function render() : string
    {
        $tpl = new ilTemplate(__DIR__ . "/templates/multi_line_new_input_gui.html", true, true);

        $remove_first_line = (!$this->required && empty($this->getValue(false)));
        $tpl->setVariable("REMOVE_FIRST_LINE", $remove_first_line);
        $tpl->setVariable("REQUIRED", $this->required);
        $tpl->setVariable("SHOW_INPUT_LABEL", $this->show_input_label);

        if (!$this->required) {
            $tpl->setCurrentBlock("add_first_line");

            if (!empty($this->getInputs())) {
                $tpl->setVariable("HIDE_ADD_FIRST_LINE", self::output()->getHTML(new ilTemplate(__DIR__ . "/templates/multi_line_new_input_gui_hide.html", false, false)));
            }

            $tpl->setVariable("ADD_FIRST_LINE", self::output()->getHTML(self::dic()->ui()->factory()->glyph()->add()->withAdditionalOnLoadCode(function (string $id) : string {
                return 'il.MultiLineNewInputGUI.init($("#' . $id . '").parent().parent().parent(), true)';
            })));

            $tpl->parseCurrentBlock();
        }

        $tpl->setCurrentBlock("line");

        foreach ($this->getInputs() as $i => $inputs) {
            if ($remove_first_line) {
                $tpl->setVariable("HIDE_LINE", self::output()->getHTML(new ilTemplate(__DIR__ . "/templates/multi_line_new_input_gui_hide.html", false, false)));
            }

            $tpl->setVariable("INPUTS", Items::renderInputs($inputs));

            if ($this->show_sort) {
                $sort_tpl = new ilTemplate(__DIR__ . "/templates/multi_line_new_input_gui_sort.html", true, true);

                $sort_tpl->setVariable("UP", self::output()->getHTML(self::dic()->ui()->factory()->glyph()->sortAscending()));
                if ($i === 0) {
                    $sort_tpl->setVariable("HIDE_UP", self::output()->getHTML(new ilTemplate(__DIR__ . "/templates/multi_line_new_input_gui_hide.html", false, false)));
                }

                $sort_tpl->setVariable("DOWN", self::output()->getHTML(self::dic()->ui()->factory()->glyph()->sortDescending()));
                if ($i === (count($this->getInputs()) - 1)) {
                    $sort_tpl->setVariable("HIDE_DOWN", self::output()->getHTML(new ilTemplate(__DIR__ . "/templates/multi_line_new_input_gui_hide.html", false, false)));
                }

                $tpl->setVariable("SORT", self::output()->getHTML($sort_tpl));
            }

            $tpl->setVariable("ADD", self::output()->getHTML(self::dic()->ui()->factory()->glyph()->add()->withAdditionalOnLoadCode(function (string $id) use ($i) : string {
                return 'il.MultiLineNewInputGUI.init($("#' . $id . '").parent().parent().parent())' . ($i === (count($this->getInputs()) - 1) ? ';il.MultiLineNewInputGUI.update($("#' . $id
                        . '").parent().parent().parent().parent())' : '');
            })));

            $tpl->setVariable("REMOVE", self::output()->getHTML(self::dic()->ui()->factory()->glyph()->remove()));
            if ($this->required && count($this->getInputs()) < 2) {
                $tpl->setVariable("HIDE_REMOVE", self::output()->getHTML(new ilTemplate(__DIR__ . "/templates/multi_line_new_input_gui_hide.html", false, false)));
            }

            $tpl->parseCurrentBlock();
        }

        return self::output()->getHTML($tpl);
    }


    /**
     * @param ilFormPropertyGUI[] $inputs
     */
    public function setInputs(array $inputs) /*: void*/
    {
        $this->inputs = $inputs;
        $this->inputs_generated = null;
    }


    /**
     * @param int $show_input_label
     */
    public function setShowInputLabel(int $show_input_label)/* : void*/
    {
        $this->show_input_label = $show_input_label;
    }


    /**
     * @param bool $show_sort
     */
    public function setShowSort(bool $show_sort)/* : void*/
    {
        $this->show_sort = $show_sort;
    }


    /**
     * @param array $value
     */
    public function setValue(/*array*/ $value)/*: void*/
    {
        if (is_array($value)) {
            $this->value = $value;
        } else {
            $this->value = [];
        }
    }


    /**
     * @param array $value
     */
    public function setValueByArray(/*array*/ $value)/*: void*/
    {
        $this->setValue($value[$this->getPostVar()]);
    }
}
