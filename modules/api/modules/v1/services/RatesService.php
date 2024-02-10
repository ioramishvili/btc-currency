<?php

declare(strict_types=1);

namespace app\modules\api\modules\v1\services;

use app\modules\api\modules\v1\components\blockchain\Client;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class RatesService extends AbstractService
{
    /**
     * @throws Exception
     * @throws InvalidConfigException
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function execute(?string $parameter = null, ?array $body = []): array
    {
        $currencies = (new Client())->request();

        if ($parameter) {
            $currency = explode(',', $parameter);
            $currencies = array_intersect_key($currencies, array_flip($currency));
        }

        asort($currencies);

        $currencies = array_map(function($value) {
            return $this->getValueWithAddedComission((float)$value['buy']);
        }, $currencies);

        if (empty($currencies)) {
            throw new NotFoundHttpException('Не найдены курсы для указанных валют.');
        }

        return $currencies;
    }

    public function allowedMethods(): array
    {
        return ['GET'];
    }
}