<?php

namespace srag\Plugins\HelpMe\Ticket;

use ilHelpMePlugin;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\HelpMe\Ticket
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository {

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
	 * Repository constructor
	 */
	private function __construct() {

	}


	/**
	 * @return Factory
	 */
	public function factory(): Factory {
		return Factory::getInstance();
	}


	/**
	 * @param string $project_url_key
	 *
	 * @return string
	 */
	public function getLink(string $project_url_key = ""): string {
		return self::supports()->getLink("tickets" . (!empty($project_url_key) ? "_" . $project_url_key : ""));
	}


	/**
	 * @param int $ticket_id
	 *
	 * @return Ticket|null
	 */
	public function getTicketById(int $ticket_id)/*: ?Ticket*/ {
		/**
		 * @var Ticket|null $ticket
		 */

		$ticket = Ticket::where([ "ticket_id" => $ticket_id ])->first();

		return $ticket;
	}


	/**
	 * @param string $ticket_key
	 *
	 * @return Ticket|null
	 */
	public function getTicketByKey(string $ticket_key)/*: ?Ticket*/ {
		/**
		 * @var Ticket|null $ticket
		 */

		$ticket = Ticket::where([ "ticket_key" => $ticket_key ])->first();

		return $ticket;
	}


	/**
	 * @param string $ticket_title
	 * @param string $ticket_project_key
	 * @param string $ticket_issue_type
	 * @param string $ticket_priority
	 *
	 * @return Ticket[]
	 */
	public function getTickets(string $ticket_title = "", string $ticket_project_key = "", string $ticket_issue_type = "", string $ticket_priority = ""): array {
		/**
		 * @var Ticket[] $tickets
		 */

		$where = Ticket::where([]);

		if (!empty($ticket_title)) {
			$where = $where->where([ "title" => '%' . $ticket_title . '%' ], "LIKE");
		}

		if (!empty($ticket_project_key)) {
			$where = $where->where([ "ticket_project_key" => $ticket_project_key ]);
		}

		if (!empty($ticket_issue_type)) {
			$where = $where->where([ "ticket_issue_type" => $ticket_issue_type ]);
		}

		if (!empty($ticket_priority)) {
			$where = $where->where([ "ticket_priority" => $ticket_priority ]);
		}

		$tickets = $where->orderBy("ticket_project_key", "ASC")->orderBy("ticket_title", "ASC")->get();

		return $tickets;
	}


	/**
	 *
	 */
	public function removeTickets()/*: void*/ {
		Ticket::truncateDB();
	}


	/**
	 * @param Ticket[] $tickets
	 */
	public function replaceWith(array $tickets)/*: void*/ {
		$this->removeTickets();

		foreach ($tickets as $ticket) {
			$this->storeInstance($ticket);
		}
	}


	/**
	 * @param Ticket $ticket
	 */
	public function storeInstance(Ticket $ticket)/*: void*/ {
		$ticket->store();
	}
}
