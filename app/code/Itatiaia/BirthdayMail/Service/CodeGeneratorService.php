<?php

namespace Itatiaia\BirthdayMail\Service;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\SalesRule\Model\Data\CouponGenerationSpec;
use Magento\SalesRule\Model\Service\CouponManagementService;

class CodeGeneratorService
{
    /**
     * @var CouponManagementService
     */
    private $service;
    /**
     * @var CouponGenerationSpec
     */
    private $generator;

    public function __construct(CouponManagementService $service, CouponGenerationSpec $generator)
    {
        $this->service = $service;
        $this->generator = $generator;
    }

    public function generateCodes()
    {
        $this->generator->setRuleId(24);
        $this->generator->setFormat('alphanum');
        $this->generator->setLength(10);
        $this->generator->setPrefix('');
        $this->generator->setSuffix('');
        $this->generator->setQuantity(1);
        try {
            return $this->service->generate($this->generator);
        } catch (InputException $e) {
            return false;
        } catch (LocalizedException $e) {
            return false;
        }
    }
}