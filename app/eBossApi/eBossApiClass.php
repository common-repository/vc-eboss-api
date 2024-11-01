<?php namespace eBossApi;

/**
 * EbossApiClient v.0.1
 *
 * Class for eBoss API
 * @see http://demo.api.recruits-online.com/
 *
 * @todo more exception handling and unit testing required
 * @Author: ebossrecruitment.com
 * @Date: 11/12/14
 * @Time: 12:26 PM
 */
class eBossApiClass
{

    const DEFAULT_HOST = 'http://demo.api.recruits-online.com/';
    const DEFAULT_VERSION = 'v-1-2';
    const DEFAULT_RESULT_PER_PAGE = 1000;

    protected $options;
    protected $regName;

    /**
     * Authentication username
     * @var
     */
    protected $username;

    /**
     * Authentication password
     * @var
     */
    protected $password;

    /**
     *
     * Authentication API key
     * @var
     */
    protected $key;


    /**
     * hostname
     * @var string
     */
    protected $host;

    /**
     *
     * API endpoint
     * @var
     */
    protected $endpoint;

    /**
     * uri
     * @var string
     */
    protected $uri;

    /**
     * API version
     * supports 1-1 and 1-2
     * @var string
     */
    protected $version;

    public function __construct()
    {
        $this->setApiOptions();
        $this->host = 'http://' . $this->regName . '.api.recruits-online.com/';
        $this->version = self::DEFAULT_VERSION;

        $this->uri = $this->host . $this->version;
    }

    public function setApiOptions()
    {
        $this->options = get_option('eBossApi');
        $this->regName = $this->options['eBossApi_rgName'];
        $this->username = $this->options['eBossApi_username'];
        $this->password = $this->options['eBossApi_password'];
        $this->key = $this->options['eBossApi_key'];
    }

    public function setHost($hostname = self::DEFAULT_HOST)
    {
        $this->host = $hostname;
    }

    public function setVersion($version = self::DEFAULT_VERSION)
    {
        $this->version = $version;
    }

    /**
     *
     *
     * set get query parameters for filtering result
     *
     * default sort order = ASC
     * @param array $params
     */
    public function setParams($params = array())
    {
        if ($params) {
            foreach ($params as $key => $value) {
                if (trim($value) !== '') {
                    $this->uri = $this->uri . '/' . $key . '/' . urlencode($value);
                }
            }
        }
    }

    /**
     * returns uri
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     *
     * @todo catch exception or consider using curl
     *
     *
     * @note uri must be set accordingly, otherwise it will fail
     * @return mixed
     */
    public function getResult()
    {
        $results = file_get_contents($this->uri);
        $resultArray = json_decode($results, true);
        return $resultArray;
    }

    /**
     *
     * upload file
     * @see http://demo.api.recruits-online.com/#page:file-operations,header:file-operations-upload-file
     *
     * @param array $params
     * @return array|mixed
     */
    public function fileUpload($params = array())
    {
        if (!$params) {
            return array();
        }

        $this->setEndpoint('/file/upload/');
        $results = $this->postRequest(null, $params);

        return json_decode($results, true);
    }

    public function setEndpoint($endpoint = null)
    {
        $this->endpoint = $endpoint;
        $this->uri = $this->uri . $endpoint . 'api_uname/' . $this->username . '/api_pass/' . $this->password . '/api_key/' . $this->key;
    }

    /**
     *
     * execute post request
     *
     * @param String $uri
     * @param array $params
     * @param array $options
     *
     * @return array|mixed
     * @throws Exception
     */
    protected function postRequest($uri = null, $params = array(), $options = array())
    {
        global $wp_version;

        $this->uri = ($uri) ? $uri : $this->uri;
        if (!$this->uri) {
            return array();
        }

        try {
            $options = wp_parse_args($options, array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url'),
                'blocking' => true,
                'headers' => array(),
                'cookies' => array(),
                'body' => $params,
                'compress' => false,
                'decompress' => true,
                'sslverify' => false,
                'stream' => false,
                'filename' => null
            ));
            $raw_result = wp_remote_request($this->uri, $options);

        } catch (Exception $e) {
            throw new Exception();
        }

        return $result = wp_remote_retrieve_body($raw_result);

    }

    /**
     *
     * process custom call
     * @see http://demo.api.recruits-online.com/#page:custom-calls
     *
     * @param String $sql
     *
     * @return array|mixed
     */
    public function processCustomCall($sql = null)
    {
        if (!$sql) {
            return array();
        }

        $data = array(
            'sql' => $sql
        );

        $this->setEndpoint('/custom/process/');
        $results = $this->postRequest($this->uri, $data);

        return json_decode($results, true);
    }

    /**
     *
     * @note fileData must be base64 encoded
     * @example
     *   $data = base64_encode(file_get_contents($_FILES['cv']['tmp_name']));
     *
     * @param  String $fileData
     *
     * @return SimpleXMLElement|string
     */
    public function parseCV($fileData = null)
    {
        if (!$fileData) {
            return '';
        }

        $data = array(
            'data' => $fileData
        );

        $this->setEndpoint('/parsecv/index/');
        $xmlResult = $this->postRequest($this->uri, $data);

        return \eBossApi\ResumeParser::convertXmlToArray($xmlResult);
    }
}
