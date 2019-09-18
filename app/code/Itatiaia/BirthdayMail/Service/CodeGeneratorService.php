<?php

namespace Itatiaia\BirthdayMail\Service;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\SalesRule\Model\Data\CouponGenerationSpec;
use Magento\SalesRule\Model\Service\CouponManagementService;
use Magento\Framework\App\Config\ScopeConfigInterface;

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
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * CodeGeneratorService constructor.
     * @param CouponManagementService $service
     * @param CouponGenerationSpec $generator
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(CouponManagementService $service, CouponGenerationSpec $generator, ScopeConfigInterface $scopeConfig)
    {
        $this->service = $service;
        $this->generator = $generator;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool|string[]
     */
    public function generateCodes()
    {
        $ruleId = $this->scopeConfig->getValue('sales/birthday_mail_group/birthday_mail_field', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->generator->setRuleId($ruleId);
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