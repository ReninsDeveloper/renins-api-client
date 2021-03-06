<?php
namespace ReninsApi\Response\Rest;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Model with VehicleTypes
 *
 * @property string $Name
 * @property ContainerCollection $VehicleTypes
 */
class ModelExt extends Container
{
    protected $rules = [
        'Name' => ['toString'],
        'VehicleTypes' => ['containerCollection:' . VehicleType::class],
    ];

    public function fromXml(\SimpleXMLElement $xml)
    {
        $this->Name = (string) $xml->Name;
        if ($xml->VehicleTypes[0]) {
            $this->VehicleTypes = ContainerCollection::createFromXml($xml->VehicleTypes[0]->Classifier, VehicleType::class);
        }

        return $this;
    }
}