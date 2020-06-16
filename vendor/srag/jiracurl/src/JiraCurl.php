<?php

namespace srag\JiraCurl\HelpMe;

use CURLFile;
use ilCurlConnection;
use ilCurlConnectionException;
use ILIAS\FileUpload\DTO\UploadResult;
use ilProxySettings;
use srag\DIC\HelpMe\DICTrait;
use srag\JiraCurl\HelpMe\Exception\JiraCurlException;
use Throwable;

/**
 * Class JiraCurl
 *
 * @package srag\JiraCurl\HelpMe
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class JiraCurl
{

    use DICTrait;

    /**
     * @var string
     */
    const AUTHORIZATION_OAUTH = "oauth";
    /**
     * @var string
     */
    const AUTHORIZATION_USERNAMEPASSWORD = "usernamepassword";
    /**
     * @var int
     */
    const MAX_RESULTS = 1000;
    /**
     * @var string
     */
    protected $jira_access_token = "";
    /**
     * @var string
     */
    protected $jira_authorization = "";
    /**
     * @var string
     */
    protected $jira_consumer_key = "";
    /**
     * @var string
     */
    protected $jira_domain = "";
    /**
     * @var string
     */
    protected $jira_password = "";
    /**
     * @var string
     */
    protected $jira_private_key = "";
    /**
     * @var string
     */
    protected $jira_username = "";


    /**
     * JiraCurl constructor
     */
    public function __construct()
    {
    }


    /**
     * Add attachements to issue ticket
     *
     * @param string         $issue_key
     * @param UploadResult[] $attachments
     *
     * @throws ilCurlConnectionException
     * @throws JiraCurlException
     */
    public function addAttachmentsToIssue(string $issue_key, array $attachments) : void
    {
        if (empty($attachments)) {
            return;
        }

        $headers = [
            "Accept"            => "application/json",
            "Content-Type"      => "multipart/form-data",
            "X-Atlassian-Token" => "nocheck"
        ];

        /*
        $data = [];
        foreach ($attachments as $i => $attachment) {
            $data ["file[" . $i . "]"] = new CURLFile($attachment->getPath(), $attachment->getMimeType(), $attachment->getName());
        }
        */

        foreach ($attachments as $i => $attachment) {
            $data = [
                "file" => new CURLFile($attachment->getPath(), $attachment->getMimeType(), $attachment->getName())
            ];

            $this->doRequest("/rest/api/2/issue/" . $issue_key . "/attachments", $headers, json_encode($data));
        }
    }


    /**
     * Add attachments to service desk request
     *
     * @param int            $service_desk_id
     * @param string         $request_ticket_key
     * @param UploadResult[] $attachments
     *
     * @throws ilCurlConnectionException
     * @throws JiraCurlException
     */
    public function addAttachmentsToServiceDeskRequest(int $service_desk_id, string $request_ticket_key, array $attachments) : void
    {
        if (empty($attachments)) {
            return;
        }

        $headers = [
            "Accept"            => "application/json",
            "Content-Type"      => "multipart/form-data",
            "X-Atlassian-Token" => "nocheck",
            "X-ExperimentalApi" => "opt-in"
        ];

        /*
        $data = [];
        foreach ($attachments as $i => $attachment) {
            $data ["file[" . $i . "]"] = new CURLFile($attachment->getPath(), $attachment->getMimeType(), $attachment->getName());
        }
        */

        $temporary_attachment_ids = [];

        foreach ($attachments as $attachment) {
            $data = [
                "file" => new CURLFile($attachment->getPath(), $attachment->getMimeType(), $attachment->getName())
            ];

            $result = $this->doRequest("/rest/servicedeskapi/servicedesk/" . $service_desk_id . "/attachTemporaryFile", $headers, $data);
            if (empty($result["temporaryAttachments"]) || !is_array($result["temporaryAttachments"])) {
                throw new JiraCurlException("Temporary attachments is empty");
            }

            $temporary_attachment_ids = array_merge($temporary_attachment_ids, array_map(function (array $temporary_attachment) : string {
                return $temporary_attachment["temporaryAttachmentId"];
            }, $result["temporaryAttachments"]));
        }

        $headers = [
            "Accept"            => "application/json",
            "Content-Type"      => "application/json",
            "X-ExperimentalApi" => "opt-in"
        ];

        $data = [
            "temporaryAttachmentIds" => $temporary_attachment_ids,
            "public"                 => true
        ];

        $this->doRequest("/rest/servicedeskapi/request/" . $request_ticket_key . "/attachment", $headers, json_encode($data));
    }


    /**
     * @param string      $issue_key
     * @param string|null $user
     *
     * @throws ilCurlConnectionException
     * @throws JiraCurlException
     */
    public function assignIssueToUser(string $issue_key, ?string $user = null) : void
    {
        $headers = [
            "Accept"       => "application/json",
            "Content-Type" => "application/json"
        ];

        $data = [
            "name" => $user
        ];

        try {
            $this->doRequest("/rest/api/2/issue/" . $issue_key . "/assignee", $headers, null, json_encode($data));
        } catch (JiraCurlException $ex) {
            if ($ex->getMessage() !== "Jira results: ") {
                throw $ex;
            }
        }
    }


    /**
     * Create Jira issue ticket
     *
     * @param string      $jira_project_key
     * @param string      $jira_issue_type
     * @param string      $summary
     * @param string      $description
     * @param string|null $priority
     * @param string|null $fix_version
     *
     * @return string Issue-Key
     *
     * @throws ilCurlConnectionException
     * @throws JiraCurlException
     */
    public function createJiraIssueTicket(string $jira_project_key, string $jira_issue_type, string $summary, string $description, string $priority = null, string $fix_version = null) : string
    {
        $headers = [
            "Accept"       => "application/json",
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
                    "name"    => $jira_issue_type,
                    "subtask" => false
                ]
            ]
        ];

        if (!empty($priority)) {
            $data["fields"]["priority"] = [
                "name" => $priority
            ];
        }

        if (!empty($fix_version)) {
            $data["fields"]["fixVersions"] = [
                [
                    "name" => $fix_version
                ]
            ];
        }

        $result = $this->doRequest("/rest/api/2/issue", $headers, json_encode($data));

        if (empty($result["key"])) {
            throw new JiraCurlException("Issue key is empty");
        }

        return $result["key"];
    }


    /**
     * Create service desk request
     *
     * @param int         $service_desk_id
     * @param int         $request_type_id
     * @param string      $summary
     * @param string      $description
     * @param string|null $customer
     *
     * @return string
     *
     * @throws ilCurlConnectionException
     * @throws JiraCurlException
     */
    public function createServiceDeskRequest(int $service_desk_id, int $request_type_id, string $summary, string $description, ?string $customer = null) : string
    {
        $headers = [
            "Accept"       => "application/json",
            "Content-Type" => "application/json"
        ];

        $data = [
            "serviceDeskId"      => $service_desk_id,
            "requestTypeId"      => $request_type_id,
            "requestFieldValues" => [
                "summary"     => $summary,
                "description" => $description
            ]
        ];
        if (!empty($customer)) {
            $data["raiseOnBehalfOf"] = $customer;
        }

        $result = $this->doRequest("/rest/servicedeskapi/request", $headers, json_encode($data));

        if (empty($result["issueKey"])) {
            throw new JiraCurlException("Issue key is empty");
        }

        return $result["issueKey"];
    }


    /**
     * Ensure service desk customer
     *
     * @param string $email
     * @param string $full_name
     *
     * @return string
     *
     * @throws ilCurlConnectionException
     * @throws JiraCurlException
     */
    public function ensureServiceDeskCustomer(string $email, string $full_name) : string
    {
        $headers = [
            "Accept"            => "application/json",
            "Content-Type"      => "application/json",
            "X-ExperimentalApi" => "opt-in"
        ];

        $data = [
            "email"    => $email,
            "fullName" => $full_name
        ];

        try {
            $result = $this->doRequest("/rest/servicedeskapi/customer", $headers, json_encode($data));
        } catch (JiraCurlException $ex) {
            if (strpos($ex->getMessage(), "A user with that username already exists") !== false) {
                return $email;
            } else {
                throw $ex;
            }
        }

        if (empty($result["name"])) {
            throw new JiraCurlException("Name is empty");
        }

        return $result["name"];
    }


    /**
     * @param string $value
     *
     * @return string
     */
    public function escapeJQLValue(string $value) : string
    {
        return '"' . addslashes($value) . '"';
    }


    /**
     * @return string
     */
    public function getJiraAccessToken() : string
    {
        return $this->jira_access_token;
    }


    /**
     * @param string $jira_access_token
     */
    public function setJiraAccessToken(string $jira_access_token) : void
    {
        $this->jira_access_token = $jira_access_token;
    }


    /**
     * @return string
     */
    public function getJiraAuthorization() : string
    {
        return $this->jira_authorization;
    }


    /**
     * @param string $jira_authorization
     */
    public function setJiraAuthorization(string $jira_authorization) : void
    {
        $this->jira_authorization = $jira_authorization;
    }


    /**
     * @return string
     */
    public function getJiraConsumerKey() : string
    {
        return $this->jira_consumer_key;
    }


    /**
     * @param string $jira_consumer_key
     */
    public function setJiraConsumerKey(string $jira_consumer_key) : void
    {
        $this->jira_consumer_key = $jira_consumer_key;
    }


    /**
     * @return string
     */
    public function getJiraDomain() : string
    {
        return $this->jira_domain;
    }


    /**
     * @param string $jira_domain
     */
    public function setJiraDomain(string $jira_domain) : void
    {
        $this->jira_domain = $jira_domain;
    }


    /**
     * @return string
     */
    public function getJiraPassword() : string
    {
        return $this->jira_password;
    }


    /**
     * @param string $jira_password
     */
    public function setJiraPassword(string $jira_password) : void
    {
        $this->jira_password = $jira_password;
    }


    /**
     * @return string
     */
    public function getJiraPrivateKey() : string
    {
        return $this->jira_private_key;
    }


    /**
     * @param string $jira_private_key
     */
    public function setJiraPrivateKey(string $jira_private_key) : void
    {
        $this->jira_private_key = $jira_private_key;
    }


    /**
     * @return string
     */
    public function getJiraUsername() : string
    {
        return $this->jira_username;
    }


    /**
     * @param string $jira_username
     */
    public function setJiraUsername(string $jira_username) : void
    {
        $this->jira_username = $jira_username;
    }


    /**
     * @param string $issue_key
     *
     * @return array
     *
     * @throws ilCurlConnectionException
     * @throws JiraCurlException
     */
    public function getTicketByKey(string $issue_key) : array
    {
        // Tickets of project
        $jql = 'key=' . $this->escapeJQLValue($issue_key);

        return current($this->getTicketsByJQL($jql));
    }


    /**
     * Get Jira tickets by JQL filter
     *
     * @param string $jql JQL
     *
     * @return array Array of jira tickets
     *
     * @throws ilCurlConnectionException
     * @throws JiraCurlException
     */
    public function getTicketsByJQL(string $jql) : array
    {
        $headers = [
            "Accept" => "application/json"
        ];

        $result = $this->doRequest("/rest/api/2/search?maxResults=" . rawurlencode(self::MAX_RESULTS) . "&jql=" . rawurlencode($jql), $headers);

        if (!is_array($result["issues"])) {
            throw new JiraCurlException("Issues array is not set");
        }

        $issues = $result["issues"];

        foreach ($issues as $issue) {
            if (!is_array($issue)) {
                throw new JiraCurlException("Issue is not an array");
            }
        }

        return $issues;
    }


    /**
     * Get Jira tickets of project
     *
     * @param string $jira_project_key   Project key
     * @param array  $filter_issue_types Filter by issue types
     *
     * @return array Array of jira tickets
     *
     * @throws ilCurlConnectionException
     * @throws JiraCurlException
     */
    public function getTicketsOfProject(string $jira_project_key, array $filter_issue_types = []) : array
    {
        // Tickets of project
        $jql = 'project=' . $this->escapeJQLValue($jira_project_key);

        // Resolution is unresolved
        $jql .= " AND resolution=unresolved";

        // No security level set
        $jql .= " AND level IS EMPTY";

        // Filter by issue types
        if (!empty($filter_issue_types)) {
            $jql .= " AND issuetype IN(" . implode(",", array_map([$this, "escapeJQLValue"], $filter_issue_types)) . ")";
        }

        // Sort by updated descending
        $jql .= " ORDER BY updated DESC";

        return $this->getTicketsByJQL($jql);
    }


    /**
     * Link tickets
     *
     * @param string $ticket_key_1
     * @param string $ticket_key_2
     * @param string $link_type
     *
     * @throws ilCurlConnectionException
     * @throws JiraCurlException
     */
    public function linkTickets(string $ticket_key_1, string $ticket_key_2, string $link_type) : void
    {
        $headers = [
            "Accept"       => "application/json",
            "Content-Type" => "application/json"
        ];

        $data = [
            "inwardIssue"  => [
                "key" => $ticket_key_1
            ],
            "outwardIssue" => [
                "key" => $ticket_key_2
            ],
            "type"         => [
                "name" => $link_type
            ]
        ];

        try {
            $this->doRequest("/rest/api/2/issueLink", $headers, json_encode($data));
        } catch (JiraCurlException $ex) {
            if ($ex->getMessage() !== "Jira results: ") {
                throw $ex;
            }
        }
    }


    /**
     * Jira request
     *
     * @param string $rest_url
     * @param array  $headers
     * @param mixed  $post_data
     * @param mixed  $put_data
     *
     * @return array
     *
     * @throws ilCurlConnectionException
     * @throws JiraCurlException
     */
    protected function doRequest(string $rest_url, array $headers, $post_data = null, $put_data = null) : array
    {
        $url = $this->jira_domain . $rest_url;

        $curlConnection = null;

        try {
            $curlConnection = $this->initCurlConnection($url, $headers);

            if ($post_data !== null) {
                $curlConnection->setOpt(CURLOPT_PUT, true);
                $curlConnection->setOpt(CURLOPT_POSTFIELDS, $post_data);
            } else {
                if ($put_data !== null) {
                    $curlConnection->setOpt(CURLOPT_CUSTOMREQUEST, "PUT");
                    $curlConnection->setOpt(CURLOPT_POSTFIELDS, $put_data);
                }
            }

            $result = $curlConnection->exec();

            $result_json = json_decode($result, true);

            if (empty($result_json) || !is_array($result_json)) {
                throw new JiraCurlException("Jira results: " . $result);
            }

            if (!empty($result_json["errorMessage"]) || !empty($result_json["errorMessages"]) || !empty($result_json["errors"])) {
                throw new JiraCurlException("Jira results errors: " . json_encode($result_json));
            }

            if (!empty($result_json["status-code"]) && intval($result_json["status-code"]) >= 400) {
                throw new JiraCurlException("Jira results errors: " . json_encode($result_json));
            }

            return $result_json;
        } finally {
            // Close Curl connection
            if ($curlConnection !== null) {
                $curlConnection->close();
                $curlConnection = null;
            }
        }
    }


    /**
     * Init a Jira Curl connection
     *
     * @param string $url
     * @param array  $headers
     *
     * @return ilCurlConnection
     *
     * @throws ilCurlConnectionException
     */
    protected function initCurlConnection(string $url, array $headers) : ilCurlConnection
    {
        $curlConnection = new ilCurlConnection();

        $curlConnection->init();

        // use a proxy, if configured by ILIAS
        if (!self::version()->is6()) {
            $proxy = ilProxySettings::_getInstance();
            if ($proxy->isActive()) {
                $curlConnection->setOpt(CURLOPT_HTTPPROXYTUNNEL, true);

                if (!empty($proxy->getHost())) {
                    $curlConnection->setOpt(CURLOPT_PROXY, $proxy->getHost());
                }

                if (!empty($proxy->getPort())) {
                    $curlConnection->setOpt(CURLOPT_PROXYPORT, $proxy->getPort());
                }
            }
        }

        $curlConnection->setOpt(CURLOPT_RETURNTRANSFER, true);
        $curlConnection->setOpt(CURLOPT_VERBOSE, false);
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
                    "oauth_consumer_key"     => $this->jira_consumer_key,
                    "oauth_nonce"            => $nonce,
                    "oauth_signature_method" => $signature_method,
                    "oauth_timestamp"        => $timestamp,
                    "oauth_token"            => $this->jira_access_token,
                    "oauth_version"          => "1.0"
                ];

                $string_to_sign = "POST&" . rawurlencode($url) . "&" . rawurlencode(implode("&", array_map(function ($key, $value) {
                        return (rawurlencode($key) . "=" . rawurlencode($value));
                    }, array_keys($o_auth), $o_auth)));

                $certificate = openssl_pkey_get_private($this->jira_private_key);
                $private_key_id = openssl_get_privatekey($certificate);

                $signature = null;
                openssl_sign($string_to_sign, $signature, $private_key_id);
                $signature = base64_encode($signature);

                try {
                    openssl_free_key($private_key_id);
                    openssl_free_key($certificate);
                } catch (Throwable $ex) {

                }

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
}
