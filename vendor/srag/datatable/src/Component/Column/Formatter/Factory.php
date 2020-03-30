<?php

namespace srag\DataTableUI\HelpMe\Component\Column\Formatter;

use srag\DataTableUI\HelpMe\Component\Column\Formatter\Actions\Factory as ActionsFactory;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\HelpMe\Component\Column\Formatter
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Factory
{

    /**
     * @return ActionsFactory
     */
    public function actions() : ActionsFactory;


    /**
     * @param array $chain
     *
     * @return Formatter
     */
    public function chainGetter(array $chain) : Formatter;


    /**
     * @return Formatter
     */
    public function check() : Formatter;


    /**
     * @return Formatter
     */
    public function date() : Formatter;


    /**
     * @return Formatter
     */
    public function default() : Formatter;


    /**
     * @param string $prefix
     *
     * @return Formatter
     */
    public function languageVariable(string $prefix) : Formatter;


    /**
     * @return Formatter
     */
    public function learningProgress() : Formatter;


    /**
     * @return Formatter
     */
    public function link() : Formatter;
}
