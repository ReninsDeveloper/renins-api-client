<?php

namespace ReninsApi\Client\Methods\V2;

use ReninsApi\Request\ValidatorMultiException;
use ReninsApi\Response\Rest\ArrayOfBrand;
use ReninsApi\Rest\Client as RestClient;

/**
 * Methods /Vehicle/Brands/*
 */
trait VehicleBrands
{
    /**
     * Method: /Vehicle/Brands/All
     * @param null|string $VehicleType
     * @return ArrayOfBrand
     * @throws \Exception
     */
    public function vehicleBrandsAll($VehicleType = null): ArrayOfBrand {
        /* @var $client RestClient */
        $client = $this->getRestClient();
        $parameters = ['VehicleType' => $VehicleType];

        $this->logMessage(__METHOD__, 'Making request', $parameters);
        try {
            $xml = $client->get('Vehicle/Brands/All', $parameters);

            $res = ArrayOfBrand::createFromXml($xml);
            $res->validateThrow();

            $this->logMessage(__METHOD__, 'Successful', [
                'request' => $client->getLastRequest(),
                'response' => $client->getLastResponse(),
            ]);
        } catch (ValidatorMultiException $exc) {
            $this->logMessage(__METHOD__, $exc->getMessage(), ['errors' => $exc->getErrors()]);
            throw $exc;
        } catch(\Exception $exc) {
            $this->logMessage(__METHOD__, $exc->getMessage(), [
                'request' => $client->getLastRequest(),
                'response' => $client->getLastResponse(),
            ]);
            throw $exc;
        }

        return $res;
    }

    /**
     * Method: /Vehicle/Brands/AllWithModels
     * @param null|string $VehicleType
     * @return ArrayOfBrand
     * @throws \Exception
     */
    public function vehicleBrandsAllWithModels($VehicleType = null): ArrayOfBrand {
        /* @var $client RestClient */
        $client = $this->getRestClient();
        $parameters = ['VehicleType' => $VehicleType];

        $this->logMessage(__METHOD__, 'Making request', $parameters);
        try {
            $xml = $client->get('Vehicle/Brands/AllWithModels', $parameters);

            $res = ArrayOfBrand::createFromXml($xml);
            $res->validateThrow();

            $this->logMessage(__METHOD__, 'Successful', [
                'request' => $client->getLastRequest(),
                'response' => $client->getLastResponse(),
            ]);
        } catch (ValidatorMultiException $exc) {
            $this->logMessage(__METHOD__, $exc->getMessage(), ['errors' => $exc->getErrors()]);
            throw $exc;
        } catch(\Exception $exc) {
            $this->logMessage(__METHOD__, $exc->getMessage(), [
                'request' => $client->getLastRequest(),
                'response' => $client->getLastResponse(),
            ]);
            throw $exc;
        }

        return ArrayOfBrand::createFromXml($xml);
    }
}