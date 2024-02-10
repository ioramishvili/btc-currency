<?php

declare(strict_types=1);

namespace app\modules\api\modules\v1\forms;

use yii\base\Model;

class ConvertForm extends Model
{
    public $currency_from;
    public $currency_to;
    public $value;

    public function rules(): array
    {
        return [
            [['currency_from', 'currency_to', 'value'], 'required'],
            [['currency_from', 'currency_to'], 'string'],
            [['value'], 'number', 'min' => 0.01],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'currency_from' => 'Валюта для продажи',
            'currency_to' => 'Валюта для покупки',
            'value' => 'Количество',
        ];
    }
}