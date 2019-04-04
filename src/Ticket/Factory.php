<?php

namespace srag\Plugins\HelpMe\Ticket;

use ilHelpMePlugin;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Support\Support;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Factory
 *
 * @package srag\Plugins\HelpMe\Ticket
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Factory {

	use DICTrait;
	use HelpMeTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
	/**
	 * @var self
	 */
	protected static $instance = null;


	/**
	 * @return self
	 */
	public static function getInstance(): self {
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Factory constructor
	 */
	private function __construct() {

	}


	/**
	 * @param array $json
	 *
	 * @return Ticket
	 */
	public function fromJiraJson(array $json): Ticket {
		$ticket = $this->newInstance();

		// TODO

		return $ticket;
	}


	/**
	 * @param Support $support
	 * @param string  $ticket_key
	 * @param string  $ticket_title
	 *
	 * @return Ticket
	 */
	public function fromSupport(Support $support, string $ticket_key, string $ticket_title): Ticket {
		$ticket = $this->newInstance();

		$ticket->setTicketKey($ticket_key);

		$ticket->setTicketTitle($ticket_title);

		$ticket->setTicketProjectKey($support->getProject()->getProjectKey());

		$ticket->setTicketIssueType($support->getProject()->getProjectIssueType());

		$ticket->setTicketPriority($support->getPriority());

		return $ticket;
	}


	/**
	 * @return Ticket
	 */
	public function newInstance(): Ticket {
		$ticket = new Ticket();

		return $ticket;
	}
}
