<?php
namespace Letto\ChatWork;

use \Carbon\Carbon;
use \Guzzle\Common\Collection;
use \Guzzle\Http\Client as GuzzleClient;

class ChatWork
{
    const BASE_URL  = 'https://api.chatwork.com/';
    const VERSION   = 'v2';

    private $token;
    private $baseUrl;
    private $version;

    protected $roomId;
    protected $messages = array();
    protected $client   = null;

    public function __construct($token = null, $baseUrl = self::BASE_URL, $version = self::VERSION)
    {
        $this->token    = $token;
        $this->baseUrl  = $baseUrl;
        $this->version  = $version;
        $this->client = new GuzzleClient($this->baseUrl);
    }

    /**
     * set Chatwork roomId
     *
     * @param   string|integer  $roomId - id of room to send
     * @return  void
     */
    public function room($roomId)
    {
        $this->roomId = $roomId;
        return $this;
    }

    /**
     * set ChatWork API Token
     *
     * @param   string  $token - ChatWork API Token
     * @return  void
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * send to message
     *
     * @param   string  $text
     * @return  boolean
     */
    public function message($text)
    {
        $this->messages[] = $text;
        return $this->send();
    }

    /**
     * send to info message
     *
     * @param   string  $title
     * @param   string  $text
     * @return  boolean
     */
    public function info($title, $text)
    {
        $params = array($title, $text);
        if ($message = self::tag('info', $params)) {
            $this->messages[] = $message;
            return $this->send();
        }
    }

    /**
     * send to code message
     *
     * @param   string  $text
     * @return  boolean
     */
    public function code($text)
    {
        if ($message = self::tag('code', $text)) {
            $this->messages[] = $message;
            return $this->send();
        }
    }

    /**
     * send to messages
     * - Send the value set in `$this->messages`
     *
     * @param   string  $text
     * @return  boolean
     */
    public function send()
    {
        return $this->request($this->roomId, '{version}/rooms/{roomId}/messages', array(
            'form_params' => array('body' => implode("\n", $this->messages)),
        ));
    }

    /**
     * add Task
     *
     * @param   string|integer  $roomId - id of room to send
     * @param   integer|array   $toIds  - UserId to add the task
     * @param   string          $text
     * @param   string          $limit  - unixtime
     * @return  boolean
     */
    public function task($toIds, $text, $limit = null)
    {
        $toIds = is_array($toIds) ? $toIds : array($toIds);
        return $this->request($this->roomId, '{version}/rooms/{roomId}/tasks', array(
            'form_params' => array(
                'to_ids'    => implode(',', $toIds),
                'body'      => implode("\n", $this->messages),
                'limit'     => $limit ? $limit : Carbon::now()->timestamp,
            ),
        ));
    }

    /**
     * request
     *
     * @param   string|integer  $roomId     - id of room to send
     * @param   string          $path       - destination (endpoint)
     * @param   string          $options    - unixtime
     * @return  boolean
     */
    protected function request($roomId, $path, $options)
    {
        $this->client->setConfig(new Collection(array(
            'roomId'    => $roomId,
            'version'   => $this->version
        )));
        $request = $this->client
            ->post($path, $this->headers());
        foreach ($options['form_params'] as $key => $val) {
            $request->setPostField($key, $val);
        }

        return $this->_send($request);
    }

    /**
     * actual condition to send
     *
     * @param   object  $request - guzzle request object
     * @return  boolean
     */
    protected function _send($request)
    {
        try {
            $response = $request->send();
            $this->messages = array();
            // return json_decode($response->getBody(), true);
            return true;
        } catch (\Exception $e) {
            // echo $e->getMessage();
            return false;
        }
    }

    /**
     * default headers
     *
     * @return array
     */
    protected function headers()
    {
        return array(
            'X-ChatWorkToken'   => $this->token,
            'timeout'           => 60,
            'debug'             => false,
        );
    }

    /**
     * create chatwork tag
     *
     * @param   string  $type   - type of tag to create
     * @param   mixed   $params - parameters to pass to the method
     * @return string|boolean
     */
    public static function tag($type, $params)
    {
        if (method_exists('\Letto\ChatWork\Tags', $type)) {
            $method = '\Letto\ChatWork\Tags::' . $type;
            if (is_string($params)) {
                $params = array($params);
            }
            return call_user_func_array($method, $params);
        }
        return false;
    }

    /**
     * create Message to send
     *
     * @param   string  $type   - type of tag to create
     * @param   mixed   $params - parameters to pass to the method
     */
    public function addMessage($type, $params)
    {
        if ($message = self::tag($type, $params)) {
            $this->messages[] = $message;
        }
        return $this;
    }
}
