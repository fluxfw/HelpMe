<?php

namespace srag\Plugins\HelpMe\Utils;

use srag\Plugins\HelpMe\Access\Access;
use srag\Plugins\HelpMe\Access\Ilias;
use srag\Plugins\HelpMe\Project\Repository as ProjectRepository;
use srag\Plugins\HelpMe\Support\Repository as SupportRepository;
use srag\Plugins\HelpMe\Ticket\Repository as TicketRepository;

/**
 * Trait HelpMeTrait
 *
 * @package srag\Plugins\HelpMe\Utils
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
trait HelpMeTrait {

	/**
	 * @return Access
	 */
	protected static function access(): Access {
		return Access::getInstance();
	}


	/**
	 * @return Ilias
	 */
	protected static function ilias(): Ilias {
		return Ilias::getInstance();
	}


	/**
	 * @return ProjectRepository
	 */
	protected static function projects(): ProjectRepository {
		return ProjectRepository::getInstance();
	}


	/**
	 * @return SupportRepository
	 */
	protected static function supports(): SupportRepository {
		return SupportRepository::getInstance();
	}


	/**
	 * @return TicketRepository
	 */
	protected static function tickets(): TicketRepository {
		return TicketRepository::getInstance();
	}
}
