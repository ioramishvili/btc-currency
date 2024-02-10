<?php

declare(strict_types=1);

namespace app\modules\api\modules\v1\services;

abstract class AbstractService
{
    protected const COMISSION = 2;
    protected const BTC_CURRENCY = 'BTC';

    abstract public function execute(?string $parameter = null, ?array $body = []): array;

    abstract public function allowedMethods(): array;

    public function validate(string $method): bool
    {
        return in_array($method, $this->allowedMethods(), true);
    }

    public function getValueWithAddedComission(float $value): float
    {
        return $value * (1 + (self::COMISSION / 100));
    }

    public function getValueWithSubtractedComission(float $value): float
    {
        return $value * ((100 - self::COMISSION) / 100);
    }
}