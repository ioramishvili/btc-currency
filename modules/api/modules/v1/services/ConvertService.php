<?php

declare(strict_types=1);

namespace app\modules\api\modules\v1\services;

use app\modules\api\modules\v1\components\blockchain\Client;
use app\modules\api\modules\v1\forms\ConvertForm;
use Yii;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;
use yii\web\ForbiddenHttpException;

class ConvertService extends AbstractService
{
    /**
     * @throws Exception
     * @throws ForbiddenHttpException
     * @throws InvalidConfigException
     * @throws ErrorException
     */
    public function execute(?string $parameter = null, ?array $body = []): array
    {
        $form = new ConvertForm();
        $form->load($body, '');

        if (!$form->validate()) {
            throw new ErrorException('Ошибка валидации данных.');
        }

        if (
            (
                $form->currency_from !== self::BTC_CURRENCY && $form->currency_to !== self::BTC_CURRENCY
            ) || (
                $form->currency_from === self::BTC_CURRENCY &&  $form->currency_to === self::BTC_CURRENCY
            )
        ) {
            throw new ErrorException('Необходимо купить или продать BTC.');
        }

        $currencies = (new Client())->request();

        $currencyFrom = $currencies[$form->currency_from] ?? null;
        $currencyTo = $currencies[$form->currency_to] ?? null;
        $value = (float) $form->value;

        if (empty($currencyFrom) && empty($currencyTo)) {
            throw new ErrorException('Не найдена валюта с которой необходимо совершить операцию.');
        }

        if ($currencyFrom) {
            $selectedCurrency = $this->getValueWithAddedComission((float)$currencyFrom['buy']);
            $result = number_format(round($value / $selectedCurrency, 10), 10);
        } else {
            $selectedCurrency = $this->getValueWithSubtractedComission((float)$currencyTo['sell']);
            $result = round($value * $selectedCurrency, 2);
        }

        return [
            'convert_from' => $form->currency_from,
            'convert_to' => $form->currency_to,
            'value' => $value,
            'converted_value' => $result,
            'rate' => $selectedCurrency,
        ];
    }

    public function allowedMethods(): array
    {
        return ['POST'];
    }
}