<?php

declare(strict_types=1);

namespace app\modules\api\modules\v1\components\blockchain;

use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\httpclient\Client as HttpClient;
use yii\httpclient\CurlTransport;
use yii\httpclient\Exception;
use yii\web\ForbiddenHttpException;

class Client extends Component
{
    private const API_BASE_URL = 'https://blockchain.info/ticker';

    protected HttpClient|null $client;

    public function init(): void
    {
        $this->client = new HttpClient([
            'baseUrl' => self::API_BASE_URL,
            'requestConfig' => [
                'headers' => [
                    'Content-Type' => 'application/json; charset=utf-8',
                    'Accept' => 'application/json',
                ],
            ],
            'transport' => CurlTransport::class,
        ]);
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     * @throws ForbiddenHttpException
     */
    public function request()
    {
        $response = $this->client->createRequest()->send();

        if ($response->isOk) {
            return $response->data;
        }

        throw new ForbiddenHttpException();
    }
}