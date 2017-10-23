<?php

require_once "Services/WebServices/Curl/classes/class.ilCurlConnection.php";

/**
 * Jira Curl connection
 */
class ilJiraCurl {

	/**
	 * @var string
	 */
	protected $jira_domain;
	/**
	 * @var string
	 */
	protected $jira_authorization;
	/**
	 * @var string
	 */
	protected $jira_username;
	/**
	 * @var string
	 */
	protected $jira_password;
	/**
	 * @var string
	 */
	protected $jira_consumer_key;


	public function __construct() {
	}


	/**
	 * Init a Jira Curl connection
	 *
	 * @return ilCurlConnection
	 */
	protected function initCurlConnection() {
		$headers = [
			"Accept" => "application/json",
			"Content-Type" => "application/json"
		];

		$curlConnection = new ilCurlConnection();

		$curlConnection->init();

		$curlConnection->setOpt(CURLOPT_RETURNTRANSFER, true);
		$curlConnection->setOpt(CURLOPT_VERBOSE, 1);
		$curlConnection->setOpt(CURLOPT_SSL_VERIFYPEER, 0);
		$curlConnection->setOpt(CURLOPT_SSL_VERIFYHOST, 0);
		$curlConnection->setOpt(CURLOPT_POST, true);

		switch ($this->jira_authorization) {
			case "usernamepassword":
				$curlConnection->setOpt(CURLOPT_USERPWD, $this->jira_username . ":" . $this->jira_password);
				break;

			case "oauth":
				$nonce = sha1(uniqid(rand(), true));
				$signature_method = "RSA-SHA1";
				$timestamp = time();

				$o_auth = [
					"oauth_consumer_key" => $this->jira_consumer_key,
					"oauth_nonce" => $nonce,
					"oauth_signature_method" => $signature_method,
					"oauth_timestamp" => $timestamp,
					"oauth_version" => "1.0"
				];

				$headers["Authorization"] = "OAuth " . implode(", ", array_map(function ($key, $value) {
						return ($key . "=" . $value);
					}, array_keys($o_auth), $o_auth));
				break;

			default:
				break;
		}

		$headers = array_map(function ($key, $value) {
			return ($key . ": " . $value);
		}, array_keys($headers), $headers);

		$curlConnection->setOpt(CURLOPT_HTTPHEADER, $headers);

		return $curlConnection;
	}


	/**
	 * Jira request
	 *
	 * @param string $rest_url
	 * @param array  $post_data
	 *
	 * @return bool
	 */
	protected function doRequest($rest_url, $post_data) {
		$curlConnection = NULL;

		try {
			$curlConnection = $this->initCurlConnection();

			$curlConnection->setOpt(CURLOPT_URL, $this->jira_domain . $rest_url);
			$curlConnection->setOpt(CURLOPT_POSTFIELDS, json_encode($post_data));

			$a = $curlConnection->exec();

			return true;
		} catch (Exception $ex) {
			return false;
		} finally {
			if ($curlConnection !== NULL) {
				$curlConnection->close();
				$curlConnection = NULL;
			}
		}
	}


	/**
	 * Create Jira ticket
	 *
	 * @param string $jira_project_key
	 * @param string $jira_issue_type
	 * @param string $summary
	 * @param string $description
	 *
	 * @return bool
	 */
	function createJiraTicket($jira_project_key, $jira_issue_type, $summary, $description) {
		$data = [
			"fields" => [
				"project" => [
					"key" => $jira_project_key,
				],

				"summary" => $summary,

				"description" => $description,

				"issuetype" => [
					"name" => $jira_issue_type,
					"subtask" => false
				]
			]
		];

		return $this->doRequest("/rest/api/2/issue", $data);
	}


	/**
	 * @return string
	 */
	public function getJiraDomain() {
		return $this->jira_domain;
	}


	/**
	 * @param string $jira_domain
	 */
	public function setJiraDomain($jira_domain) {
		$this->jira_domain = $jira_domain;
	}


	/**
	 * @return string
	 */
	public function getJiraAuthorization() {
		return $this->jira_authorization;
	}


	/**
	 * @param string $jira_authorization
	 */
	public function setJiraAuthorization($jira_authorization) {
		$this->jira_authorization = $jira_authorization;
	}


	/**
	 * @return string
	 */
	public function getJiraUsername() {
		return $this->jira_username;
	}


	/**
	 * @param string $jira_username
	 */
	public function setJiraUsername($jira_username) {
		$this->jira_username = $jira_username;
	}


	/**
	 * @return string
	 */
	public function getJiraPassword() {
		return $this->jira_password;
	}


	/**
	 * @param string $jira_password
	 */
	public function setJiraPassword($jira_password) {
		$this->jira_password = $jira_password;
	}


	/**
	 * @return string
	 */
	public function getJiraConsumerKey() {
		return $this->jira_consumer_key;
	}


	/**
	 * @param string $jira_consumer_key
	 */
	public function setJiraConsumerKey($jira_consumer_key) {
		$this->jira_consumer_key = $jira_consumer_key;
	}
}
