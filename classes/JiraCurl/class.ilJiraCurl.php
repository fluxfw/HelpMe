<?php

use srag\DIC\DICTrait;

/**
 * Class ilJiraCurl
 */
class ilJiraCurl {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
	const AUTHORIZATION_USERNAMEPASSWORD = "usernamepassword";
	const AUTHORIZATION_OAUTH = "oauth";
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
	/**
	 * @var string
	 */
	protected $jira_private_key;
	/**
	 * @var string
	 */
	protected $jira_access_token;


	/**
	 * ilJiraCurl constructor
	 */
	public function __construct() {
	}


	/**
	 * Init a Jira Curl connection
	 *
	 * @param string $url
	 * @param array  $headers
	 *
	 * @return ilCurlConnection
	 */
	protected function initCurlConnection($url, $headers) {
		$curlConnection = new ilCurlConnection();

		$curlConnection->init();

		$curlConnection->setOpt(CURLOPT_RETURNTRANSFER, true);
		$curlConnection->setOpt(CURLOPT_VERBOSE, true);
		$curlConnection->setOpt(CURLOPT_SSL_VERIFYPEER, false);
		$curlConnection->setOpt(CURLOPT_SSL_VERIFYHOST, false);
		$curlConnection->setOpt(CURLOPT_URL, $url);

		switch ($this->jira_authorization) {
			case self::AUTHORIZATION_USERNAMEPASSWORD:
				$curlConnection->setOpt(CURLOPT_USERPWD, $this->jira_username . ":" . $this->jira_password);
				break;

			case self::AUTHORIZATION_OAUTH:
				$nonce = sha1(uniqid("", true) . $url);
				$signature_method = "RSA-SHA1";
				$timestamp = time();

				$o_auth = [
					"oauth_consumer_key" => $this->jira_consumer_key,
					"oauth_nonce" => $nonce,
					"oauth_signature_method" => $signature_method,
					"oauth_timestamp" => $timestamp,
					"oauth_token" => $this->jira_access_token,
					"oauth_version" => "1.0"
				];

				$string_to_sign = "POST&" . rawurlencode($url) . "&" . rawurlencode(implode("&", array_map(function ($key, $value) {
						return (rawurlencode($key) . "=" . rawurlencode($value));
					}, array_keys($o_auth), $o_auth)));

				$certificate = openssl_pkey_get_private($this->jira_private_key);
				$private_key_id = openssl_get_privatekey($certificate);

				$signature = NULL;
				openssl_sign($string_to_sign, $signature, $private_key_id);
				$signature = base64_encode($signature);

				openssl_free_key($private_key_id);
				openssl_free_key($certificate);

				$o_auth["oauth_signature"] = $signature;

				$headers["Authorization"] = "OAuth " . implode(", ", array_map(function ($key, $value) {
						return (urlencode($key) . '="' . urlencode($value) . '"');
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
	 * @param array  $headers
	 * @param mixed  $post_data
	 *
	 * @return array|false
	 */
	protected function doRequest($rest_url, $headers, $post_data = NULL) {
		$url = $this->jira_domain . $rest_url;

		$curlConnection = NULL;

		try {
			$curlConnection = $this->initCurlConnection($url, $headers);

			if ($post_data !== NULL) {
				$curlConnection->setOpt(CURLOPT_POST, true);
				$curlConnection->setOpt(CURLOPT_POSTFIELDS, $post_data);
			}

			$result = $curlConnection->exec();

			$result = json_decode($result, true);
			if (!is_array($result)) {
				return false;
			}

			return $result;
		} catch (Exception $ex) {
			// Curl-Error!
			return false;
		} finally {
			// Close Curl connection
			if ($curlConnection !== NULL) {
				$curlConnection->close();
				$curlConnection = NULL;
			}
		}
	}


	/**
	 * Create Jira issue ticket
	 *
	 * @param string $jira_project_key
	 * @param string $jira_issue_type
	 * @param string $summary
	 * @param string $description
	 *
	 * @return string|false Issue-Key
	 */
	public function createJiraIssueTicket($jira_project_key, $jira_issue_type, $summary, $description) {
		$headers = [
			"Accept" => "application/json",
			"Content-Type" => "application/json"
		];

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

		$result = $this->doRequest("/rest/api/2/issue", $headers, json_encode($data));

		if ($result === false || !isset($result["key"])) {
			return false;
		}

		$issue_key = $result["key"];

		return $issue_key;
	}


	/**
	 * Add attachement to issue ticket
	 *
	 * @param string $issue_key
	 * @param string $attachement_name
	 * @param string $attachement_mime
	 * @param string $attachement_path
	 *
	 * @return bool
	 */
	public function addAttachmentToIssue($issue_key, $attachement_name, $attachement_mime, $attachement_path) {
		$headers = [
			"Accept" => "application/json",
			"X-Atlassian-Token" => "nocheck"
		];

		$data = [
			"file" => new CURLFile($attachement_path, $attachement_mime, $attachement_name)
		];

		$result = $this->doRequest("/rest/api/2/issue/" . $issue_key . "/attachments", $headers, $data);

		return ($result !== false);
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


	/**
	 * @return string
	 */
	public function getJiraPrivateKey() {
		return $this->jira_private_key;
	}


	/**
	 * @param string $jira_private_key
	 */
	public function setJiraPrivateKey($jira_private_key) {
		$this->jira_private_key = $jira_private_key;
	}


	/**
	 * @return string
	 */
	public function getJiraAccessToken() {
		return $this->jira_access_token;
	}


	/**
	 * @param string $jira_access_token
	 */
	public function setJiraAccessToken($jira_access_token) {
		$this->jira_access_token = $jira_access_token;
	}
}
