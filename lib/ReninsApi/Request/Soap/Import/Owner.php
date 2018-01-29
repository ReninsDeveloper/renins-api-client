<?php

namespace ReninsApi\Request\Soap\Import;

/**
 * Владелец ТС.
 *
 * @property double $KBM - Коэффициент бонус-малус
 */
class Owner extends ContactInfo
{
    protected function init()
    {
        parent::init();
        $this->rules = array_merge($this->rules, [
            'KBM' => ['toDouble', 'kbm'],
        ]);
    }

    public function toXml(\SimpleXMLElement $xml)
    {
        parent::toXml($xml);
        $this->toXmlAttributes($xml, ['KBM']);
        return $this;
    }
}
