<?php

namespace ReninsApiTest\Client\V2;

use PHPUnit\Framework\TestCase;
use ReninsApi\Request\ContainerCollection;
use ReninsApiTest\Client\Log;

class ProlongationCascoTest extends TestCase
{
    use Log;
	
	var $prolongationNumber = '001AT-18/00341-S';
	var $insurantName = 'Иванова';

    private function getDateBegin() 
	{
        return (new \DateTime())->add(new \DateInterval('P2D'))
            ->setTime(12, 0, 0);
    }

    private function getDateEnd() 
	{
        $dtBegin = $this->getDateBegin();
        $dtBegin->add(new \DateInterval('P1Y'))->sub(new \DateInterval('P1D'))->setTime(23, 59, 59);
        return $dtBegin;
    }
	
	private function createPolicy()
	{
		
		$ContractTerm = new \ReninsApi\Request\Soap\Calculation\ContractTerm();
        $ContractTerm->Product = 1;
        $ContractTerm->ProgramType = 'KASKO';
        $ContractTerm->DurationMonth = 12;
        $ContractTerm->PeriodUseMonth = $ContractTerm->DurationMonth;
        $ContractTerm->Periods = new ContainerCollection([
            new \ReninsApi\Request\Soap\Calculation\Period([
                'UseDateBegin' => $this->getDateBegin()->format('Y-m-d'),
                'UseDateEnd' => $this->getDateEnd()->format('Y-m-d')
            ])
        ]);
        $ContractTerm->PaymentType = 1;
        $ContractTerm->Purpose = 'личная';

        $Covers = new ContainerCollection();
        $Covers->add(new \ReninsApi\Request\Soap\Calculation\Cover(['code' => 'UGON', 'sum' => 300000]));
        $Covers->add(new \ReninsApi\Request\Soap\Calculation\Cover(['code' => 'USHERB', 'sum' => 300000]));
        $Covers->add(new \ReninsApi\Request\Soap\Calculation\Cover(['code' => 'DO', 'sum' => 100000]));
        $Covers->add(new \ReninsApi\Request\Soap\Calculation\Cover(['code' => 'NS', 'sum' => 100000]));

        $Vehicle = new \ReninsApi\Request\Soap\Calculation\Vehicle();
        $Vehicle->Manufacturer = 'ВАЗ';
        $Vehicle->Model = '1117 Kalina';
        $Vehicle->Year = date('Y');
        $Vehicle->Cost = 400000;
        $Vehicle->Type = 'Легковое ТС';
        $Vehicle->AntiTheftDeviceInfo = new \ReninsApi\Request\Soap\Calculation\AntiTheftDeviceInfo([
            'AntiTheftDeviceBrand' => 'Цезарь Сателлит',
            'AntiTheftDeviceModel' => 'Cesar 200',
        ]);
        $Vehicle->PUUDeviceInfo = new \ReninsApi\Request\Soap\Calculation\PUUDeviceInfo([
            'PUUDeviceModel' => 'Sky Brake + доп. блок-р рулевого вала', //SmartCode 2,4-1immo + доп. замок АКПП
        ]);
        $Vehicle->RightWheel = false;
        $Vehicle->ManufacturerType = 1;
        $Vehicle->IsNew = false;
        $Vehicle->IsTaxi = false;
        $Vehicle->Power = 98;
        $Vehicle->GrossWeight = 1500;
        $Vehicle->PassangerCapacity = 4;
        $Vehicle->Category = 'B';
        $Vehicle->CarBodyType = 'Седан';
        $Vehicle->TransmissionType = 'Механическая';
        $Vehicle->EngineType = 'Бензиновый';
        $Vehicle->CarIdent = new \ReninsApi\Request\Soap\Calculation\CarIdent([
            'LicensePlate' => 'У123ЕО163',
        ]);
        $Vehicle->UseTrailer = false;

        $Drivers = new \ReninsApi\Request\Soap\Calculation\Drivers();
        $Drivers->Multidrive = false;
        $Drivers->Driver = new ContainerCollection([
            new \ReninsApi\Request\Soap\Calculation\Contact([
                'LastName' => 'Иванов',
                'FirstName' => 'Иван',
                'MiddleName' => 'Иванович',
                'BirthDate' => '1980-05-11',
                'DriveExperience' => '2001-01-01',
                'Gender' => 'М',
                'MaritalStatus' => 1,
                'HasChildren' => true,
                'Documents' => new ContainerCollection([
                    new \ReninsApi\Request\Soap\Calculation\Document([
                        'type' => 'DRIVING_LICENCE',
                        'Serial' => '1234',
                        'Number' => '123456',
                    ])
                ]),
            ]),
            new \ReninsApi\Request\Soap\Calculation\Contact([
                'LastName' => 'Иванова',
                'FirstName' => 'Наталья',
                'MiddleName' => 'Владимировна',
                'BirthDate' => '1980-06-01',
                'DriveExperience' => '2001-01-01',
                'Gender' => 'Ж',
                'MaritalStatus' => 3,
                'HasChildren' => true,
                'Documents' => new ContainerCollection([
                    new \ReninsApi\Request\Soap\Calculation\Document([
                        'type' => 'DRIVING_LICENCE',
                        'Serial' => '1255',
                        'Number' => '654321',
                    ])
                ]),
            ])
        ]);

        $Insurant = new \ReninsApi\Request\Soap\Calculation\Insurant();
        $Insurant->type = 1;

        $Participants = new \ReninsApi\Request\Soap\Calculation\Participants();
        $Participants->Drivers = $Drivers;
        $Participants->Insurant = $Insurant;
        $Participants->BeneficiaryType = 1;
        $Participants->Prospect = new \ReninsApi\Request\Soap\Calculation\Prospect([
            'LastName' => 'Иванова',
            'FirstName' => 'Наталья',
            'MiddleName' => 'Владимировна',
            'Phone' => '+79171450000',
            'Email' => 'abc@abc.ru',
        ]);
        $Participants->Owner = new \ReninsApi\Request\Soap\Calculation\Owner([
            'type' => 1,
            'Contact' => new \ReninsApi\Request\Soap\Calculation\Contact([
                'LastName' => 'Иванова',
                'FirstName' => 'Наталья',
                'MiddleName' => 'Владимировна',
                'BirthDate' => '1980-06-01',
                'DriveExperience' => '2001-01-01',
                'Gender' => 'Ж',
                'MaritalStatus' => 3,
                'HasChildren' => true,
                'Documents' => new ContainerCollection([
                    new \ReninsApi\Request\Soap\Calculation\Document([
                        'type' => 'DRIVING_LICENCE',
                        'Serial' => '1255',
                        'Number' => '654321',
                    ])
                ]),
            ])
        ]);
        $Participants->Lessee = new \ReninsApi\Request\Soap\Calculation\Lessee([
            'type' => 2,
            'Account' => new \ReninsApi\Request\Soap\Calculation\Account([
                'Name' => "ООО \"Рога и копыта\"",
                'INN' => '123456789',
            ])
        ]);

        $Stoa = new ContainerCollection([
            new \ReninsApi\Request\Soap\Calculation\StoaType(['type' => 3, 'enabled' => false]),
            new \ReninsApi\Request\Soap\Calculation\StoaType(['type' => 2, 'enabled' => true]),
        ]);

		$Casco = new \ReninsApi\Request\Soap\Calculation\Casco([
            'Stoa' => new ContainerCollection([
                new \ReninsApi\Request\Soap\Calculation\StoaType([
                    'type' => 3,
                    'enabled' => false,
                ]),
                new \ReninsApi\Request\Soap\Calculation\StoaType([
                    'type' => 6,
                    'enabled' => true,
                ])
            ])
        ]);
		
		$Policy = new \ReninsApi\Request\Soap\Calculation\Policy();
		$Policy->ContractTerm = $ContractTerm;
        $Policy->Covers = $Covers;
        $Policy->Vehicle = $Vehicle;
        $Policy->Participants = $Participants;
        $Policy->Casco = $Casco;
		
		return $Policy;
	}
	
	/**
     * Достаточно полная структура по расчету КАСКО. Без франшизы. С лизингом. С телематикой.
     * @return \ReninsApi\Request\Soap\Calculation\Request
     */
    private function getImportRequest() {
        $dt = new \DateTime();
        $dtMinusDay = (clone $dt)->sub(new \DateInterval('P1D'));

        $generalQuoteInfo = new \ReninsApi\Request\Soap\Import\GeneralQuoteInfo();
        $generalQuoteInfo->SALE_DATE = $dt->format('Y-m-d') . 'T12:00:00';
        $generalQuoteInfo->CURRENCY = 'RUR';

        $partner = new \ReninsApi\Request\Soap\Import\Partner();
        $partner->NAME = 'Link_Auto';
        $partner->DEPARTMENT = '001';
        $partner->DIVISION = 'Москва';
        $partner->FILIAL = 'Москва';

        $seller = new \ReninsApi\Request\Soap\Import\Seller();
        $seller->TYPE = 'PARTNER';
        $seller->PARTNER = $partner;
        $seller->MANAGERS = new ContainerCollection([
            new \ReninsApi\Request\Soap\Import\Manager(['name' => 'Иванов И.И.']),
        ]);

        $contact = new \ReninsApi\Request\Soap\Import\Contact();
        $contact->IPFLAG = false;
        $contact->HOME_PHONE = '+74957654321';
        $contact->CELL_PHONE = '+74957654321';
        $contact->LAST_NAME = 'Иванова';
        $contact->FIRST_NAME = 'Наталья';
        $contact->MIDDLE_NAME = 'Владимировна';
        $contact->BIRTH_DATE = '1980-06-01';
        $contact->RESIDENT = true;
        $contact->CONTACT_ADDRESSES = new ContainerCollection([
            new \ReninsApi\Request\Soap\Import\Address([
                'TYPE' => 'ADDR_CON_REG',
                'COUNTRY' => 'Российская Федерация',
                'CITY' => 'Тольятти',
                'STREET' => 'Автостроителей',
                'HOUSE' => '11',
                'APPARTMENT' => '1',
            ]),
        ]);
        $contact->CONTACT_DOCUMENTS = new ContainerCollection([
            new \ReninsApi\Request\Soap\Import\Document([
                'TYPE' => 'PASSPORT',
                'SERIES' => '1234',
                'NUMBER' => '123456',
                'ISSUED_DATE' => '1998-01-01',
                'ISSUED_WHERE' => 'РОВД',
            ])
        ]);

        $insurant = new \ReninsApi\Request\Soap\Import\ContactInfo();
        $insurant->TYPE = 'CONTACT';
        $insurant->CONTACT = $contact;

        $privateQuoteInfo = new \ReninsApi\Request\Soap\Import\PrivateQuoteInfo();
        $privateQuoteInfo->DOCUMENT_OF_PAYMENT = new \ReninsApi\Request\Soap\Import\DocumentOfPayment([
            'TYPE' => 'по квитанции СБЕРБАНКА',
            'PAY_DOC_NUMBER' => '0123456789',
            'PAY_DOC_ISSUE_DATE' => $dtMinusDay->format('Y-m-d'),
        ]);
        $privateQuoteInfo->POLICY_NUMBER = ''; //get by GetPolicyNumber()
        $privateQuoteInfo->BSO_NUMBER = '1234567';
        $privateQuoteInfo->CREATION_DATE = $dtMinusDay->format('Y-m-d');
        $privateQuoteInfo->INS_DATE_FROM = $this->getDateBegin()->format('Y-m-d');
        $privateQuoteInfo->INS_TIME_FROM = $this->getDateBegin()->format('H:i:s');
        $privateQuoteInfo->INS_DATE_TO = $this->getDateEnd()->format('Y-m-d');
        $privateQuoteInfo->INS_TIME_TO = $this->getDateEnd()->format('H:i:s');
        $privateQuoteInfo->CURRENCY = 'RUR';
        $privateQuoteInfo->INS_DURATION = 12;
        $privateQuoteInfo->TOTALLY = false;
        $privateQuoteInfo->PRE_INSURANCE_INSPECTION = new \ReninsApi\Request\Soap\Import\PreInsuranceInspection([
            'NEW_OBJECT' => false,
            'INSPECTION_IS_NEEDED' => true,
            'INSPECTION_NOT_NEEDED_OLD_OBJECT' => false,
        ]);
        $privateQuoteInfo->RISKS = new \ReninsApi\Request\Soap\Import\Risks([
            'RISK' => new ContainerCollection([
            ]),
        ]);

        $vehicle = new \ReninsApi\Request\Soap\Import\Vehicle();
        $vehicle->TYPE = 'Легковое ТС';
        $vehicle->BRAND = 'ВАЗ';
        $vehicle->MODEL = '1117 Kalina';
        $vehicle->PRICE = '400000';
        $vehicle->POWER = '98';
        $vehicle->YEAR = date('Y');
        $vehicle->VIN = 'AB1CDE23FGH456789';
        $vehicle->REG_SIGN = 'У123ЕО163';
        $vehicle->COLOR = 'Серебристый';
        $vehicle->IS_LEASE = false;
        $vehicle->IS_CREDIT = false;
        $vehicle->PURPOSE = 'личная';
        $vehicle->KEY_COUNT = 2;
        $vehicle->ENGINE_VOLUME = '1598';
        $vehicle->ENGINE_TYPE = 'Бензиновый';
        $vehicle->TRANSMISSION_TYPE = 'МКПП';
        $vehicle->VEHICLE_BODY_TYPE = 'Седан';
        $vehicle->VEHICLE_DOCUMENTS = new ContainerCollection([
            new \ReninsApi\Request\Soap\Import\Document([
                'TYPE' => 'PTS',
                'SERIES' => '40НТ',
                'NUMBER' => '123456',
            ]),
        ]);
        $vehicle->EXTRAS = new ContainerCollection([
            new \ReninsApi\Request\Soap\Import\Equipment([
                'MARK' => 'Марка',
                'MODEL' => 'Модель',
                'AMOUNT' => 1,
                'COST' => 100000,
            ]),
        ]);

        $owner = new \ReninsApi\Request\Soap\Import\Owner();
        $owner->TYPE = 'CONTACT';
        $owner->CONTACT = $contact;

        $context = new \ReninsApi\Request\Soap\Import\Context();
        $context->PRIVATE_QUOTE_INFO = $privateQuoteInfo;
        $context->VEHICLE = $vehicle;
        $context->OWNER = $owner;
        $context->BENEFICIARIES = new \ReninsApi\Request\Soap\Import\Beneficiaries([
            'BENEFICIARY' => new ContainerCollection([
                new \ReninsApi\Request\Soap\Import\Beneficiary([
                    'TYPE' => 'CONTACT',
                    'CONTACT' => $contact,
                ]),
            ]),
        ]);

        $context->DRIVERS = new \ReninsApi\Request\Soap\Import\Drivers([
            'MULTI_DRIVE' => false,
            'STAFF' => false,

            'DRIVER' => new ContainerCollection([
                new \ReninsApi\Request\Soap\Import\Driver([
                    'CONTACT' => new \ReninsApi\Request\Soap\Import\Contact([
                        'LAST_NAME' => 'Иванов',
                        'FIRST_NAME' => 'Иван',
                        'MIDDLE_NAME' => 'Иванович',
                        'BIRTH_DATE' => '1980-05-11',
                        'DRIVE_EXPERIENCE' => '2001-01-01',
                        'GENDER' => 'М',
                        'MARITAL_STATUS' => 1,
                        'HAS_CHILDREN' => true,
                        'CONTACT_DOCUMENTS' => new ContainerCollection([
                            new \ReninsApi\Request\Soap\Import\Document([
                                'TYPE' => 'DRIVING_LICENCE',
                                'SERIES' => '1234',
                                'NUMBER' => '123456',
                            ])
                        ])
                    ])
                ]),

                new \ReninsApi\Request\Soap\Import\Driver([
                    'CONTACT' => new \ReninsApi\Request\Soap\Import\Contact([
                        'LAST_NAME' => 'Иванова',
                        'FIRST_NAME' => 'Наталья',
                        'MIDDLE_NAME' => 'Владимировна',
                        'BIRTH_DATE' => '1980-06-01',
                        'DRIVE_EXPERIENCE' => '2001-01-01',
                        'GENDER' => 'Ж',
                        'MARITAL_STATUS' => 3,
                        'HAS_CHILDREN' => true,
                        'CONTACT_DOCUMENTS' => new ContainerCollection([
                            new \ReninsApi\Request\Soap\Import\Document([
                                'TYPE' => 'DRIVING_LICENCE',
                                'SERIES' => '1255',
                                'NUMBER' => '654321',
                            ])
                        ])
                    ])
                ])
            ]),
        ]);

        $inputMessage = new \ReninsApi\Request\Soap\Import\InputMessage();
        $inputMessage->GENERAL_QUOTE_INFO = $generalQuoteInfo;
        $inputMessage->SELLER = $seller;
        $inputMessage->INSURANT = $insurant;
        $inputMessage->LIST_OF_CONTEXTS = new ContainerCollection([
            $context
        ]);

        return $inputMessage;
    }
	
    /**
     * @group prolongation-casco
     */
	public function testCalc()
    {
		$client = $this->createApi2();
		
		$Policy = $this->createPolicy();
		
		$request = new \ReninsApi\Request\Soap\Calculation\Request();
        $request->type = 1;
        $request->genUuid();
        $request->Policy = $Policy;
        $request->Prolongation = new \ReninsApi\Request\Soap\Calculation\Prolongation([
            'prolongationNumber' => $this->prolongationNumber,
            'insurantName' => $this->insurantName,
            'AutomaticProlongation' => true,
        ]);

        $response = $client->calc($request);

        ob_start();
		$data = ob_get_clean();
        @file_put_contents(TEMP_DIR . '/CascoCalcResponseProlongation.txt', $data);

        $this->assertEquals($response->isSuccessful(), true);
        $this->assertGreaterThan(0, $response->CalcResults->count());

        //Получим первый успешный
        $calcResults = $response->getFirstSuccessfulResults();
        $this->assertNotNull($calcResults);
        $this->assertGreaterThan(0, strlen($calcResults->AccountNumber));
        $this->assertEquals('true', $calcResults->Risks->Visible);
        $this->assertEquals('true', $calcResults->Risks->Enabled);
        $this->assertGreaterThan(0, strlen($calcResults->Risks->PacketName));
        $this->assertNotNull($calcResults->Risks->Risk);
        $this->assertGreaterThan(0, $calcResults->Risks->Risk->count());

        @file_put_contents(TEMP_DIR . '/CascoCalcResultsProlongation.txt', serialize($calcResults->toArray())); //Результаты расчета для импорта
        @file_put_contents(TEMP_DIR . '/CascoAccountNumberProlongation.txt', $calcResults->AccountNumber); //Номер котировки
        @file_put_contents(TEMP_DIR . '/CascoPrintTokenProlongation.txt', $response->printToken); //Токен печати
    }

    /**
     * Получить номер полиса для импорта, импорт данных
     * @group prolongation-casco
     */
    public function testGetPolicyNumber()
    {
        $client = $this->createApi2();

        $accountNumber = @file_get_contents(TEMP_DIR . '/CascoAccountNumberProlongation.txt');
        if (!$accountNumber) {
            throw new \Exception("AccountNumber isn't calculated.");
        }
		
		$request = new \ReninsApi\Request\Soap\Import\Request();
        $request->AccountNumber = $accountNumber;
        $response = $client->GetPolicyNumber($request);
		
		@file_put_contents(TEMP_DIR . '/CascoPolicyNumberProlongation.txt', $response->Number); //Номер, который нужен при импорте

		//import polis data
		$calcResults = @file_get_contents(TEMP_DIR . '/CascoCalcResultsProlongation.txt');
        if (!$calcResults) {
            throw new \Exception("CalcResults aren't calculated.");
        }
        $calcResults = @unserialize($calcResults);
        if (!is_array($calcResults)) {
            throw new \Exception("CalcResults has invalid format");
        }

		$policyNumber = $response->Number; // or @file_get_contents(TEMP_DIR . '/CascoPolicyNumberProlongation.txt');
        if (!$policyNumber) {
            throw new \Exception("PolicyNumber isn't calculated.");
        }

        $request = $this->getImportRequest();
		$request->GENERAL_QUOTE_INFO->ACCOUNT_NUMBER_CALCBASED_ON = $accountNumber;
        $request->GENERAL_QUOTE_INFO->PACKET_CALCBASED_ON = $calcResults['Risks']['PacketName'];
        $request->GENERAL_QUOTE_INFO->INSURANCE_SUM = $calcResults['Total']['Sum'];

        $context = $request->LIST_OF_CONTEXTS->get(0);
        $context->PRIVATE_QUOTE_INFO->POLICY_NUMBER = $policyNumber;
        $context->PRIVATE_QUOTE_INFO->INSURANCE_SUM = $calcResults['Total']['Sum'];

        $context->PRIVATE_QUOTE_INFO->RISKS->BONUS = $calcResults['Total']['Bonus'];
        foreach ($calcResults['Risks']['Risk'] as $risk) {
            $riskObj = new \ReninsApi\Request\Soap\Import\Risk();
            $riskObj->NAME = $risk['Name'];
            $riskObj->BONUS = $risk['Bonus'];
            $riskObj->INSURANCE_SUM = $risk['Sum'];
            $context->PRIVATE_QUOTE_INFO->RISKS->RISK->add($riskObj);
		}
		
		$response = $client->ImportPolicy($request);
		$this->assertGreaterThan(0, strlen($response->PolicyId));
        $this->assertGreaterThan(0, $response->AvailableDocumentTypes->count());
    }

    /**
     * @group prolongation-casco
     */
    public function testGetAvailablePolicyDocumentTypes()
    {
        $client = $this->createApi2();

        $accountNumber = @file_get_contents(TEMP_DIR . '/CascoAccountNumberProlongation.txt');
        if (!$accountNumber) {
            throw new \Exception("AccountNumber isn't calculated. Run calculation tests before.");
        }

        $param = new \ReninsApi\Request\Soap\Printing\Request();
        $param->AccountNumber = $accountNumber;
        $param->isPrintAsOneDocument = true;

        $response = $client->getAvailablePolicyDocumentTypes($param);
        $this->assertEquals(true, $response->Success);
        $this->assertGreaterThan(0, $response->PolicyDocumentTypes->count());
    }

    /**
     * Печать результатов расчета
     * @group prolongation-casco
     */
    public function testPrePrint()
    {
        $client = $this->createApi2();

        $accountNumber = @file_get_contents(TEMP_DIR . '/CascoAccountNumberProlongation.txt');
        if (!$accountNumber) {
            throw new \Exception("AccountNumber isn't calculated.");
        }

        $param = new \ReninsApi\Request\Soap\Printing\Request();
        $param->AccountNumber = $accountNumber;
        $param->isPrintAsOneDocument = true;
        $param->printingParamsItems = new ContainerCollection([
            new \ReninsApi\Request\Soap\Printing\PrintingParams(['DocumentTypeId' => 1]),
        ]);

        $response = $client->printDocumentsToBinary($param);

        $this->assertGreaterThan(0, $response->DocBytesResponseEx->count());
        $docBytesResponseEx = $response->DocBytesResponseEx->get(0);
        $this->assertEquals(true, $docBytesResponseEx->Success);
        $this->assertNotNull($docBytesResponseEx->Result);

        @file_put_contents(TEMP_DIR . '/CascoCalcResultsProlongation.pdf', $docBytesResponseEx->Result->DocBytes); //печатная форма "Результаты расчета", pdf
    }

    /**
     * Печать бланка полиса
     * @group prolongation-casco
     */
    public function testFinishPrint()
    {
        $client = $this->createApi2();

        $accountNumber = @file_get_contents(TEMP_DIR . '/CascoAccountNumberProlongation.txt');
        if (!$accountNumber) {
            throw new \Exception("AccountNumber isn't calculated.");
        }
        $printToken = @file_get_contents(TEMP_DIR . '/CascoPrintTokenProlongation.txt');
        if (!$printToken) {
            throw new \Exception("PrintToken isn't calculated.");
        }

        $param = new \ReninsApi\Request\Soap\Printing\Request();
        $param->AccountNumber = $accountNumber;
        $param->isPrintAsOneDocument = true;
        $param->printingParamsItems = new ContainerCollection([
            new \ReninsApi\Request\Soap\Printing\PrintingParams([
                'DocumentTypeId' => 7,
                'DocumentLabels' => ['NewCasco', 'HasStamp', 'HaveStamp'],
            ]),
        ]);
        $param->PrintToken = $printToken;

        $response = $client->printDocumentsToBinary($param);

        $this->assertGreaterThan(0, $response->DocBytesResponseEx->count());
        // @var DocBytesResponseEx $docBytesResponseEx 
        $docBytesResponseEx = $response->DocBytesResponseEx->get(0);
        $this->assertEquals(true, $docBytesResponseEx->Success);
        $this->assertNotNull($docBytesResponseEx->Result);

        @file_put_contents(TEMP_DIR . '/CascoPolisProlongation.pdf', $docBytesResponseEx->Result->DocBytes); //печатная форма "Бланк полиса", pdf
    }

}