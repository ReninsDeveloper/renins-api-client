<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Котировка
 *
 * @property string $externalId
 * @property ContractTerm $ContractTerm - Условия договора
 * @property ContainerCollection $Covers - Покрытия
 * @property Vehicle $Vehicle - ТС
 * @property Participants $Participants - Участники договора
 * @property Casco $Casco - Блок для КАСКО
 * @property Osago $Osago - Блок для ОСАГО
 */
class Policy extends Container
{
    protected $rules = [
        'externalId' => ['toString'],

        'ContractTerm' => ['container:' . ContractTerm::class, 'required'],
        'Covers' => ['containerCollection:' . Cover::class],
        'Vehicle' => ['container:' . Vehicle::class, 'required'],
        'Participants' => ['container:' . Participants::class],
        'Casco' => ['container:' . Casco::class],
        'Osago' => ['container:' . Osago::class],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['externalId']);
        $this->toXmlTagsExcept($xml, ['externalId']);
        return $this;
    }

}