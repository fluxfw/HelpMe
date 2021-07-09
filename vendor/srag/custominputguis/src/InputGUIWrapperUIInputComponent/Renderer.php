<?php

namespace srag\CustomInputGUIs\HelpMe\InputGUIWrapperUIInputComponent;

use ilHiddenInputGUI;
use ILIAS\UI\Component\Component;
use ILIAS\UI\Implementation\Component\Input\Field\Input;
use ILIAS\UI\Implementation\Render\Template;
use ILIAS\UI\Renderer as RendererInterface;

/**
 * Class Renderer
 *
 * @package srag\CustomInputGUIs\HelpMe\InputGUIWrapperUIInputComponent
 */
class Renderer extends AbstractRenderer
{

    /**
     * @inheritDoc
     */
    public function render(Component $component, RendererInterface $default_renderer) : string
    {
        if ($component->getInput() instanceof ilHiddenInputGUI) {
            return "";
        }

        $input_tpl = $this->getTemplate("input.html", true, true);

        $html = $this->renderInputFieldWithContext($default_renderer, $input_tpl, $component, null, null);

        return $html;
    }


    /**
     * @inheritDoc
     */
    protected function renderInputField(Template $tpl, Input $input, $id, RendererInterface $default_renderer) : string
    {
        return $this->renderInput($tpl, $input);
    }
}
