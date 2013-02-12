<?php
require_once(Yii::getPathOfAlias('application.vendors.Buzz.lib.Buzz') . '/ClassLoader.php');
Buzz\ClassLoader::register();
/**
 * GitHubClient
 *
 * @author f0t0n
 */
class GitHubClient extends CComponent implements IGitHubIssue,
        IGitHubIssueComment, IGitHubRepo {

    const GITHUB_BASE_URL = 'https://github.com/';
    const API_URL = 'https://api.github.com/';
    const REGEX_URL = '#^https?://\S+\.\S+$#';
    const REGEX_LINK_HEADER_VALUE =
        '/<[^>]+(?<=\&|\?)page=(\d+)[^>]*>; rel="(\w+)"/i';
    const REGEX_STATUS_HEADER_VALUE = '/^\s*?(\d+)\s+?([\s\S]*?)\s*?$/';

    const ACCEPT_HEADER = 'Accept: application/json';
    const CONTENT_TYPE_HEADER =
        'Content-type: application/x-www-form-urlencoded;charset=UTF-8';

    protected $apiUrl;
    protected $authToken;
    /**
     *
     * @var Buzz\Message\Response
     */
    protected $lastResponse;
    protected $lastResponseHeaders;
    protected $lastError;
    /**
     *
     * @var Buzz\Browser
     */
    protected $transport;
    
    /**
     * @var User
     */
    protected $user;

    public function __construct(User $user = null) {
        if(!empty($user)) {
            $this->authToken = $user->github_auth_token;
        }
        $this->transport = new Buzz\Browser();
    }

    public function setAuthToken($authToken) {
        $this->authToken = $authToken;
    }

    /**
     *
     * @return array
     * @throws CException
     * @throws GithubException
     */
    public function getUser() {
        return $this->get('user');
    }

    /**
     *
     * @return array
     * @throws CException
     * @throws GithubException
     */
    public function getRepositories($page = 1, $per_page = 30) {
        return $this->get('user/repos', array(
            'page'=>$page,
            'per_page'=>$per_page
        ));
    }

    public static function getGitHubParam($key) {
        return isset(Yii::app()->params['github'][$key])
            ? Yii::app()->params['github'][$key]
            : null;
    }

    /**
     *
     * @param string $route
     * @param array $data
     * @return array
     * @throws CException
     * @throws GithubException
     */
    public function get($route, $data = array()) {
        $headers = array(
            self::ACCEPT_HEADER
        );
        $url = $this->getRequestUrl($route) . '&' . http_build_query($data);
        $response = $this->transport->get($url, $headers);
		return $this->handleResponse($response);
    }

    /**
     *
     * @param string $route
     * @param array $data
     * @return array
     * @throws CException
     * @throws GithubException
     */
    public function post($route, $data = array(), $jsonEncode = true) {
        $headers = array(
            self::ACCEPT_HEADER
        );
        $url = $this->getRequestUrl($route);
        $response = $this->transport->post($url, $headers,
                $jsonEncode ? CJSON::encode($data) : http_build_query($data));
		return $this->handleResponse($response);
    }

    public function delete($route, $data = array()) {
        $headers = array(
            self::ACCEPT_HEADER
        );
        $url = $this->getRequestUrl($route);
        $response = $this->transport->delete($url, $headers,
                CJSON::encode($data));
		return $this->handleResponse($response);
    }

    public function patch($route, $data = array()) {
        $headers = array(
            self::ACCEPT_HEADER
        );
        $url = $this->getRequestUrl($route);
        $response = $this->transport->patch($url, $headers,
                CJSON::encode($data));
		return $this->handleResponse($response);
    }

    public function getPages() {
        return empty($this->lastResponseHeaders['Link'])
            ? null
            : $this->parseLinkHeaderValue($this->lastResponseHeaders['Link']);
    }

    public function getLastResponseHeaders() {
        return $this->lastResponseHeaders;
    }

    public function getPageLinks() {
        $linkHeaderValue = isset($this->lastResponseHeaders['Link'])
            ? $this->lastResponseHeaders['Link']
            : '';
        return $this->parsePageLinksFromHeader($linkHeaderValue);
    }

    /**
     * Returns the URL of GitHub repository.
     * 
     * @param string $fullRepoName The full name of repository with the username
     * in format <b>username/reponame</b>.<br />
     * For example: <b>torvalds/linux</b>
     *
     * @return string The URL of GitHub repository. <br />
     * For example, if <b>$fullRepoName</b> is <b>torvalds/linux</b>, <br />
     * the method will return the URL <b>https://github.com/torvalds/linux</b>.
     */
    public static function getGitHubRepoUrl($fullRepoName) {
        return self::GITHUB_BASE_URL . trim($fullRepoName, '/ ');
    }

    //                                          Interfaces implementation

    /**
     *
     * POST /repos/:user/:repo/issues
     * 
     * @param string $fullRepoName
     * @param string $title
     * @param string $body
     * @param string $assignee
     * @param int $milestone
     * @param array $labels
     * @return array
     * @see http://developer.github.com/v3/issues/#create-an-issue
     */
    public function createIssue($fullRepoName, $title, $body = null,
            $assignee = null, $milestone = null, $labels = array()) {
        $route = 'repos/' . self::repoName($fullRepoName) . '/issues';
        return $this->post($route, array(
            'title'=>$title,
            'body'=>$body,
            'assignee'=>$assignee,
            'milestone'=>$milestone,
            'labels'=>$labels
        ));
    }

    /**
     * PATCH /repos/:user/:repo/issues/:number
     *
     * @param string $fullRepoName
     * @param int $number
     * @param string $title
     * @param string $body
     * @param string $assignee Login for the user that this issue should be assigned to.
     * @param int $milestone
     * @param array $labels
     * @return array
     * @see http://developer.github.com/v3/issues/#edit-an-issue
     */
    public function editIssue($fullRepoName, $number, $title = null,
            $body = null, $assignee = null, $state = null,
            $milestone = null, $labels = array()) {
        $route = '/repos/'. self::repoName($fullRepoName) .'/issues/' . $number;
        return $this->patch($route, array(
            'title'=>$title,
            'body'=>$body,
            'assignee'=>$assignee,
            'state'=>$state,
            'milestone'=>$milestone,
            'labels'=>$labels,
        ));
    }

    /**
     * GET /repos/:user/:repo/issues/:number
     * @param string $fullRepoName
     * @param int $number
     * @return array
     * @see http://developer.github.com/v3/issues/#get-a-single-issue
     */
    public function getIssue($fullRepoName, $number) {
        return $this->get(
            '/repos/' . self::repoName($fullRepoName) . '/issues/' . $number);
    }

    /**
     * POST /repos/:user/:repo/issues/:number/comments
     *
     * @param type $fullRepoName
     * @param type $issueNumber
     * @param type $body
     *
     * @see http://developer.github.com/v3/issues/comments/#create-a-comment
     */
    public function createComment($fullRepoName, $issueNumber, $body) {
        $route = 'repos/' . self::repoName($fullRepoName)
            . '/issues/' . $issueNumber . '/comments';
        return $this->post($route, array(
            'body'=>$body,
        ));
    }

    /**
     * GET /repos/:user/:repo/collaborators/:user
     *
     * @param string $fullRepoName
     * @param string $login
     * @return array
     * @see http://developer.github.com/v3/repos/collaborators/#get
     */
    public function getCollaborator($fullRepoName, $login) {
        $route = '/repos/' . self::repoName($fullRepoName)
            . '/collaborators/' . $login;
        $response = $this->get($route);
        return $this->lastResponse->getStatusCode() > 200 ? false : $response;
    }

    /**
     * GET /repos/:user/:repo/collaborators
     *
     * @param string $fullRepoName
     * @return array
     * @see http://developer.github.com/v3/repos/collaborators/#list
     */
    public function getCollaborators($fullRepoName) {
        $route = '/repos/' . self::repoName($fullRepoName) . '/collaborators/';
        return $this->get($route);
    }

    //                                          End of interfaces implementation

    /**
     * Performs the parsing of Link HTTP header's value like this
     * <https://api.github.com/user/repos?page=3&per_page=100>; rel="next",
     * <https://api.github.com/user/repos?page=50&per_page=100>; rel="last"
     *
     * @param string $linkHeaderValue
     * @return array 
     */
    protected function parsePageLinksFromHeader($linkHeaderValue) {
        $linksArray = array(
            'first'=>null, 'prev'=>null, 'last'=>null, 'next'=>null,);
        if(preg_match_all(
                self::REGEX_LINK_HEADER_VALUE, $linkHeaderValue, $m)) {
            for($i = count($m[1]); --$i >= 0;) {
                $linksArray[$m[2][$i]] = $m[1][$i];
            }
        }
        return $linksArray;
    }

    protected static function repoName($repoName) {
        return trim($repoName, '/ ');
    }

    protected function parseHeaders($headersStr) {
        if(function_exists('http_parse_headers')) {
            return http_parse_headers($headersStr);
        }
        $retVal = array();
        $fields = explode(
            "\r\n",
            preg_replace('/\x0D\x0A[\x09\x20]+/', ' ', $headersStr)
        );
        foreach($fields as $field) {
            if(preg_match('/([^:]+): (.+)/m', $field, $match)) {
                $match[1] = preg_replace(
                    '/(?<=^|[\x09\x20\x2D])./e',
                    'strtoupper("\0")',
                    strtolower(trim($match[1]))
                );
                if(isset($retVal[$match[1]])) {
                    $retVal[$match[1]] = array($retVal[$match[1]], $match[2]);
                } else {
                    $retVal[$match[1]] = trim($match[2]);
                }
            }
        }
        return $retVal;
    }

    protected function prepareRequest() {
        $this->lastError = null;
        $this->lastResponseHeaders = null;
        $this->setCurlOption(CURLOPT_HEADER, true);
        $this->setCurlOption(CURLOPT_FAILONERROR, false);
        if(null !== ($capath = Yii::app()->params['ssl'][CURLOPT_CAPATH])) {
            $this->setCurlOption(CURLOPT_CAPATH, $capath);
        }
        $this->addRequestHeader(self::ACCEPT_HEADER);
    }

    protected function setCurlOption($option, $value) {
        Yii::app()->CURL->options['setOptions'][$option] = $value;
    }

    /**
     * @param \Buzz\Message\Response $response
     * @return array
     * @throws CException
     * @throws GithubException
     */
    protected function handleResponse(\Buzz\Message\Response $response) {
        $this->lastResponse = $response;
        $this->lastResponseHeaders = $this->parseHeaders(
                implode('', $response->getHeaders()));
        $responseContent = CJSON::decode($response->getContent());
        if(!is_array($responseContent)) {
            throw new GithubException('Wrong response retrieved.');
        } else if(!empty($responseContent['error'])) {
            $this->lastError = $responseContent['error'];
            throw new GithubException($responseContent['error']);
        }
        $status = $response->getStatusCode();
        if($status >= ErrorType::BAD_REQUEST) {
            $this->lastError = $response->getHeader('Status');
            throw new GithubException($this->lastError, $status);
        }
        return $responseContent;
    }

    protected function getResponseStatus() {
        $headers = $this->getLastResponseHeaders();
        if(isset($headers['Status'])
                && preg_match(self::REGEX_STATUS_HEADER_VALUE,
                        $headers['Status'], $matches)) {
            return array(
                'code'=>$matches[1],
                'status'=>$matches[2],
            );
        }
        return null;
    }

    protected function getRequestUrl($route) {
        return preg_match(self::REGEX_URL, $route)
            ? $route
            : self::API_URL . trim($route, '/ ')
                . '?access_token=' . $this->authToken;
    }

    protected function addRequestHeaders(array $headers) {
        foreach($headers as $header) {
            $this->addRequestHeader($header);
        }
    }

    protected function addRequestHeader($header) {
        Yii::app()->CURL->options['setOptions'][CURLOPT_HTTPHEADER][] = $header;
    }
}