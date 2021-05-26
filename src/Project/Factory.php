<?php

namespace srag\Plugins\HelpMe\Project;

use ilHelpMePlugin;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Factory
 *
 * @package srag\Plugins\HelpMe\Project
 */
final class Factory
{

    use DICTrait;
    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Factory constructor
     */
    private function __construct()
    {

    }


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @param ProjectConfigGUI $parent
     * @param Project          $project
     *
     * @return ProjectFormGUI
     */
    public function newFormInstance(ProjectConfigGUI $parent, Project $project) : ProjectFormGUI
    {
        $form = new ProjectFormGUI($parent, $project);

        return $form;
    }


    /**
     * @return Project
     */
    public function newInstance() : Project
    {
        $ticket = new Project();

        return $ticket;
    }


    /**
     * @param ProjectsConfigGUI $parent
     * @param string            $cmd
     *
     * @return ProjectsTableGUI
     */
    public function newTableInstance(ProjectsConfigGUI $parent, string $cmd = ProjectsConfigGUI::CMD_LIST_PROJECTS) : ProjectsTableGUI
    {
        $table = new ProjectsTableGUI($parent, $cmd);

        return $table;
    }
}
