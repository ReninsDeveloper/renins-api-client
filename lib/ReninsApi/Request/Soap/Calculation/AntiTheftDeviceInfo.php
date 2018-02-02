<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;

/**
 * Поисковая система
 *
 * @property string $AntiTheftDeviceBrand - Марка
 * @property string $AntiTheftDeviceModel - Модель
 * @property string $AntiTheftTrackerBrand - Марка закладки
 * @property string $AntiTheftTrackerModel - Модель закладки
 */
class AntiTheftDeviceInfo extends Container
{
    protected $rules = [
        'AntiTheftDeviceBrand' => ['toString'],
        'AntiTheftDeviceModel' => ['toString'],
        'AntiTheftTrackerBrand' => ['toString'],
        'AntiTheftTrackerModel' => ['toString'],
    ];
}