<?php

namespace Itatiaia\BirthdayMail\Service;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\SalesRule\Model\Data\CouponGenerationSpec;
use Magento\SalesRule\Model\Service\CouponManagementService;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Psr\Log\LoggerInterface;

class CodeGeneratorService
{
    /**
     * @var LoggerInterface
     */
    private $logger;
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
    public function __construct(CouponManagementService $service, CouponGenerationSpec $generator, ScopeConfigInterface $scopeConfig, LoggerInterface $logger)
    {
        $this->service = $service;
        $this->generator = $generator;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    /**
     * @return bool|string[]
     */
    public function generateCodes()
    {
        $ruleId = $this->scopeConfig->getValue('sales/birthday_mail_group/birthday_mail_field', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($ruleId != null) {
            $this->generator->setRuleId($ruleId);
            $this->generator->setFormat('alphanum');
            $this->generator->setLength(10);
            $this->generator->setPrefix('');
            $this->generator->setSuffix('');
            $this->generator->setQuantity(1);
        } else {
//            $this->logger->notice('Cupom não configurado no admin');
            return false;
        }
        try {
            return $this->service->generate($this->generator);
        } catch (InputException $e) {
//            $this->logger->notice($e->getMessage());
            return false;
        } catch (LocalizedException $e) {
//            $this->logger->notice($e->getMessage());
            return false;
        }
    }
}