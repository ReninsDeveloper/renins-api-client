<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;

/**
 * Платежный документ.
 *
 * @property string $TYPE - Тип (по квитанции А7, по квитанции А7 3, по квитанции СБЕРБАНКА, ..., другое)
 * @property string $PAY_DOC_NUMBER - Номер платежного документа.
 * @property string $PAY_DOC_ISSUE_DATE - Дата выдачи платежного документа.
 */
class DocumentOfPayment extends Container
{
    protected $rules = [
        'TYPE' => ['toString', 'required', 'in:по квитанции А7|по квитанции А7 3|по квитанции СБЕРБАНКА|по счету на оплату ОСАГО|по счету на оплату КАСКО|по счету на оплату ДАГО|другое'],

        'PAY_DOC_NUMBER' => ['toString', 'required', 'notEmpty'],
        'PAY_DOC_ISSUE_DATE' => ['toString', 'date'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['TYPE']);
        $this->toXmlTags($xml, ['PAY_DOC_NUMBER', 'PAY_DOC_ISSUE_DATE']);
        return $this;
    }

}