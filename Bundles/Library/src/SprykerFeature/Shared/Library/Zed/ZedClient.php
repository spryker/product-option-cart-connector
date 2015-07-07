<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Zed;

use Guzzle\Http\Client;
use Guzzle\Http\Message\EntityEnclosingRequest;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Cookie\Cookie;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Guzzle\Plugin\Cookie\CookiePlugin;
use SprykerFeature\Shared\Library\Communication\ObjectInterface;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\Library\System;
use SprykerFeature\Shared\Library\Communication\Request;
use SprykerEngine\Shared\Transfer\TransferInterface;
use SprykerFeature\Shared\Library\Zed\Exception\InvalidZedResponseException;
use \SprykerFeature\Shared\Lumberjack\Code\Lumberjack;
use \SprykerFeature\Shared\Lumberjack\Code\Log\Types;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Shared\Yves\YvesConfig;

class ZedClient
{

    /**
     * @var bool
     */
    protected static $alreadyRequested = false;

    /**
     * @var int
     */
    protected static $requestCounter = 0;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var int in seconds
     */
    protected static $timeoutInSeconds = 10;

    /**
     * @param string $baseUrl
     * @param string $username
     * @param string $password
     */
    public function __construct($baseUrl, $username = null, $password = null)
    {
        $this->baseUrl = $baseUrl;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @param int $timeoutInSeconds
     */
    public static function setDefaultTimeout($timeoutInSeconds)
    {
        self::$timeoutInSeconds = $timeoutInSeconds;
    }

    /**
     * @param string $pathInfo
     * @param TransferInterface $transferObject
     * @param array $metaTransfers
     * @param null $timeoutInSeconds
     * @param bool $isBackgroundRequest
     * @return \SprykerFeature\Shared\Library\Communication\Response
     * @throws \LogicException
     */
    public function request($pathInfo, TransferInterface $transferObject = null, array $metaTransfers = [], $timeoutInSeconds = null, $isBackgroundRequest = false)
    {
        if (!$this->isRequestAllowed($isBackgroundRequest)) {
            throw new \LogicException('You cannot make more than one request from Yves to Zed.');
        }
        self::$requestCounter++;

        $requestTransfer = $this->createRequestTransfer($transferObject, $metaTransfers);
        $request = $this->createGuzzleRequest($pathInfo, $requestTransfer, $timeoutInSeconds);
        $this->logRequest($pathInfo, $requestTransfer, $request->getBody());

        $this->forwardDebugSession($request);
        $response = $this->sendRequest($request);
        $responseTransfer = $this->getTransferFromResponse($response);
        $this->logResponse($pathInfo, $responseTransfer, $response->getBody(true));

        return $responseTransfer;
    }

    /**
     * @param $pathInfo
     * @return bool
     */
    protected function isLoggingAllowed($pathInfo)
    {
        return strpos($pathInfo, 'heartbeat');
    }

    /**
     * @param bool $isBackgroundRequest
     * @return bool
     */
    protected function isRequestAllowed($isBackgroundRequest)
    {
        if (!$isBackgroundRequest) {
            if (true === self::$alreadyRequested) {
                return false;
            }
            self::$alreadyRequested = true;
        }
        return true;
    }

    /**
     * @param string $pathInfo
     * @param Request $requestTransfer
     * @param int $timeoutInSeconds
     * @return EntityEnclosingRequest
     */
    protected function createGuzzleRequest($pathInfo, Request $requestTransfer, $timeoutInSeconds = null)
    {
        $client = new Client(
            $this->baseUrl,
            [
                Client::REQUEST_OPTIONS => [
                    'timeout' => ($timeoutInSeconds ? : self::$timeoutInSeconds),
                    'connect_timeout' => 1.5
                ]
            ]
        );

        $char = (strpos($pathInfo,'?')===false)?'?':'&';
        $pathInfo .= $char.'yvesRequestId='.Lumberjack::getInstance()->getRequestId();

        $client->setUserAgent('Yves 2.0');
        /** @var EntityEnclosingRequest $request */
        $request = $client->post($pathInfo);
        $request->addHeader('X-Yves-Host', 1);

        $rawRequestBody = json_encode($requestTransfer->toArray(false));

        $request->setBody($rawRequestBody, 'application/json');
        //$request->setHeader('Host', System::getHostname());

        return $request;
    }

    /**
     * @param TransferInterface $transferObject
     * @param array $metaTransfers
     * @return Request
     * @throws \LogicException
     */
    protected function createRequestTransfer(TransferInterface $transferObject, array $metaTransfers)
    {
        $request = new Request();
        $request->setSessionId(session_id());
        $request->setTime(time());
        $request->setHost(System::getHostname()?: 'n/a');

        foreach ($metaTransfers as $name => $metaTransfer) {
            if (!is_string($name) || is_numeric($name) || !$metaTransfer instanceof TransferInterface) {
                throw new \LogicException('Adding MetaTransfer failed. Either name missing/invalid or no object of TransferInterface provided.');
            }
            $request->addMetaTransfer($name, $metaTransfer);
        }
        if (!empty($this->username)) {
            $request->setUsername($this->username);
        }
        if (!empty($this->password)) {
            $request->setPassword($this->password);
        }
        if (!empty($transferObject)) {
            $request->setTransfer($transferObject);
        }
        return $request;
    }

    /**
     * @param EntityEnclosingRequest $request
     * @return Response
     * @throws Exception\InvalidZedResponseException
     */
    protected function sendRequest(EntityEnclosingRequest $request)
    {
        $response = $request->send();
        if (!$response || !$response->isSuccessful() || !$response->getBody()->getSize()) {
            throw new InvalidZedResponseException('empty', $response);
        }

        return $response;
    }

    /**
     * @param Response $response
     * @return \SprykerFeature\Shared\Library\Communication\Response
     * @throws Exception\InvalidZedResponseException
     */
    protected function getTransferFromResponse(Response $response)
    {
        $data = json_decode(trim($response->getBody(true)), true);
        if (empty($data) || !is_array($data)) {
            throw new InvalidZedResponseException('no valid JSON', $response);
        }

        $responseTransfer = new \SprykerFeature\Shared\Library\Communication\Response();
        $responseTransfer->fromArray($data);
        return $responseTransfer;
    }

    /**
     * @param string $pathInfo
     * @param Request $requestTransfer
     * @param string $rawBody
     */
    protected function logRequest($pathInfo, Request $requestTransfer, $rawBody)
    {
        $this->doLog($pathInfo, Types::TRANSFER_REQUEST, $requestTransfer, $rawBody);
    }

    /**
     * @param string $pathInfo
     * @param \SprykerFeature\Shared\Library\Communication\Response $responseTransfer
     * @param string $rawBody
     */
    protected function logResponse($pathInfo, \SprykerFeature\Shared\Library\Communication\Response $responseTransfer, $rawBody)
    {
        $this->doLog($pathInfo, Types::TRANSFER_RESPONSE, $responseTransfer, $rawBody);
    }

    /**
     * @param string $pathInfo
     * @param string $subType
     * @param ObjectInterface $transfer
     * @param string $rawBody
     */
    protected function doLog($pathInfo, $subType, ObjectInterface $transfer, $rawBody)
    {
        $lumberjack = Lumberjack::getInstance();
        $responseTransfer = $transfer->getTransfer();
        if ($responseTransfer instanceof TransferInterface) {
            $lumberjack->addField('transferData', $responseTransfer->toArray());
            $lumberjack->addField('transferClass', get_class($responseTransfer));
        } else {
            $lumberjack->addField('transferData', null);
            $lumberjack->addField('transferClass', null);
        }
        $lumberjack->addField('rawBody', $rawBody);

        $lumberjack->send(Types::TRANSFER, $pathInfo, $subType);
    }

    /**
     * Used for debug output
     * @return int
     */
    public static function getRequestCounter()
    {
        return self::$requestCounter;
    }

    /**
     * @param EntityEnclosingRequest $request
     */
    protected function forwardDebugSession(EntityEnclosingRequest $request)
    {
        if (Config::get(YvesConfig::TRANSFER_DEBUG_SESSION_FORWARD_ENABLED)) {
            $cookie = new Cookie();
            $cookie->setName(trim(Config::get(YvesConfig::TRANSFER_DEBUG_SESSION_NAME)));
            $cookie->setValue($_COOKIE[Config::get(YvesConfig::TRANSFER_DEBUG_SESSION_NAME)]);
            $cookie->setDomain(Config::get(SystemConfig::HOST_ZED_API));
            $cookieArray = new ArrayCookieJar(true);
            $cookieArray->add($cookie);

            $request->addSubscriber(new CookiePlugin($cookieArray));
        }
    }
}
