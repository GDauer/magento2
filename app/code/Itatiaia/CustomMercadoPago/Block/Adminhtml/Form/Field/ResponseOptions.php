<?php

namespace Itatiaia\CustomMercadoPago\Block\Adminhtml\Form\Field;

use Magento\Framework\View\Element\Html\Select;

class ResponseOptions extends Select
{
    protected $_options = [
        "accredited" => "Pagamento Aprovado",
        "pending_contingency" => "Contingência",
        "pending_review_manual" => "Revisão Manual",
        "pending_capture" => "Pendente de Captura",
        "cc_rejected_bad_filled_card_number" => "Número do Cartão Inválido",
        "cc_rejected_bad_filled_date" => "Data de Validade Inválida",
        "cc_rejected_bad_filled_other" => "Outros Dados Inválidos",
        "cc_rejected_bad_filled_security_code" => "Código de Segurança Inválido",
        "cc_rejected_blacklist" => "Blacklist",
        "cc_rejected_call_for_authorize" => "Ligar para Autorizar",
        "cc_rejected_card_disabled" => "Cartão Desabilitado",
        "cc_rejected_card_error" => "Erro no Cartão",
        "cc_rejected_duplicated_payment" => "Pagamento Duplicado",
        "cc_rejected_high_risk" => "Alto Risco",
        "cc_rejected_insufficient_amount" => "Saldo Insuficiente",
        "cc_rejected_invalid_installments" => "Sem Parcelamento Disponível",
        "cc_rejected_max_attempts" => "Limite de Tentativas Atingida",
        "cc_rejected_other_reason" => "Outras Razões"
    ];

    public function setInputName($value)
    {
        return $this->setName($value);
    }
}