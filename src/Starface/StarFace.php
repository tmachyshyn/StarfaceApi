<?php
namespace Starface;

use Exception;
use Starface\Api\Service;
use Starface\Client\Client;
use fXmlRpc\ClientInterface;
use Starface\Api\Connection;
use Starface\Api\CallRequests;
use Starface\Api\GroupRequests;
use Starface\Api\PhoneRequests;
use Starface\Api\UserStateRequests;

class StarFace
{
    private string $id;

    private string $authToken;

    private string $baseUrl;

    private bool $isLoggedIn = false;

    private ClientInterface $client;

    private array $data = [];

    private int $lastConnectionTime = 0;

    private string $apiVersion;

    private string $url;

    public function __construct(
        string $id,
        string $authToken,
        string $baseUrl,
        $callback = null,
        ?string $apiVersion = 'v30'
    ) {
        $this->id = $id;
        $this->baseUrl = $baseUrl;
        $this->authToken = $authToken;
        $this->apiVersion = $apiVersion;

        $this->url = $this->baseUrl .
            '/xml-rpc?de.vertico.starface.user=' . $this->id .
            '&de.vertico.starface.auth=' . $this->authToken .
            $this->getCallbackParams($callback);

        $this->client = new Client($this->url);
    }

    /**
     * @return bool
     */
    public function login()
    {
        $this->isLoggedIn = (bool) $this->getConnectionApi()->login();

        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->debug('Starface: Callback URL [' . $this->url . '].');
        }

        return $this->isLoggedIn;
    }

    /**
     * @return bool
     */
    public function logout()
    {
        $this->isLoggedIn = !$this->getConnectionApi()->logout();

        return $this->isLoggedIn;
    }

    /**
     * @return bool
     */
    public function keepAlive()
    {
        $this->isLoggedIn = $this->getConnectionApi()->keepAlive();

        return (bool) $this->isLoggedIn;
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        $timeDiff = time() - $this->getLastConnectionTime();

        if (!$this->isLoggedIn || $timeDiff >= 50) {
            $this->isLoggedIn = true;
            $this->isLoggedIn = (bool) $this->getConnectionApi()->keepAlive();
        }

        return (bool) $this->isLoggedIn;
    }

    protected function get($name)
    {
        $name = ucfirst($name);

        if (!isset($this->data[$name])) {
            $className = '\\Starface\\Api\\' . $name;

            if (!class_exists($className)) {
                throw new Exception('StarFace: Class [' . $className . '] does not exists.');
            }

            $this->data[$name] = new $className(
                $this->client,
                $this,
                $this->apiVersion
            );
        }

        return $this->data[$name];
    }

    /** @return Connection */
    protected function getConnectionApi()
    {
        return $this->get('Connection');
    }

    /** @return CallRequests */
    public function getCallApi()
    {
        return $this->get('CallRequests');
    }

    /** @return PhoneRequests */
    public function getPhoneApi()
    {
        return $this->get('PhoneRequests');
    }

    /** @return Service */
    public function getServiceApi()
    {
        return $this->get('Service');
    }

    /** @return UserStateRequests */
    public function getUserStateApi()
    {
        return $this->get('UserStateRequests');
    }

    /** @return GroupRequests */
    public function getGroupApi()
    {
        return $this->get('GroupRequests');
    }

    protected function getCallbackParams($params)
    {
        $url = '';

        if (!empty($params)) {
            foreach ($params as $name => $value) {
                $url .= '&de.vertico.starface.callback.' . $name . '=' . urlencode($value);
            }
        }

        return $url;
    }

    protected function getLastConnectionTime()
    {
        return $this->lastConnectionTime;
    }

    public function updateConnectionTime()
    {
        $this->lastConnectionTime = time();
    }
}
