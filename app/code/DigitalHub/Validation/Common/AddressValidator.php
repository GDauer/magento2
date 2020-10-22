<?php


namespace DigitalHub\Validation\Common;

use DigitalHub\Validation\Common\Validators\ComplementValidator;
use DigitalHub\Validation\Common\Validators\NeighborhoodValidator;
use DigitalHub\Validation\Common\Validators\NumberValidator;
use DigitalHub\Validation\Common\Validators\StreetValidator;

class AddressValidator
{
    /**
     * @var string[]
     */
    private $fields = [
        0 => 'Endereço',
        1 => 'Número',
        3 => 'Bairro'
    ];

    /**
     * @var ComplementValidator
     */
    protected $complementValidator;

    /**
     * @var NeighborhoodValidator
     */
    protected $neighborhoodValidator;

    /**
     * @var NumberValidator
     */
    protected $numberValidator;

    /**
     * @var StreetValidator
     */
    protected $streetValidator;


    /**
     * AddressValidator constructor.
     * @param ComplementValidator $complementValidator
     * @param NeighborhoodValidator $neighborhoodValidator
     * @param NumberValidator $numberValidator
     * @param StreetValidator $streetValidator
     */
    public function __construct(
        ComplementValidator $complementValidator,
        NeighborhoodValidator $neighborhoodValidator,
        NumberValidator $numberValidator,
        StreetValidator $streetValidator
    ) {
        $this->complementValidator = $complementValidator;
        $this->neighborhoodValidator = $neighborhoodValidator;
        $this->numberValidator = $numberValidator;
        $this->streetValidator = $streetValidator;
    }

    /**
     * @param $address
     * @return array|bool[]
     */
    public function isValid($address)
    {
        foreach ($this->fields as $field => $label) {
            if (is_numeric($field)) {
                if (empty($address->getStreet()[$field])){
                    return [
                        'isValid' => false,
                        'message' => "O campo {$label} é obrigatório.",
                    ];
                }
            } else {
                if (empty($address->getData($field))) {
                    return [
                        'isValid' => false,
                        'message' => "O campo {$label} é obrigatório.",
                    ];
                }
            }
        }

        if (!empty($address->getStreet()[2]) && !$this->complementValidator->isValid($address->getStreet()[2])) {
            return [
                'isValid' => false,
                'message' => 'Por favor digite menos de 10 caracteres no campo Complemento.',
            ];
        }

        if (!$this->neighborhoodValidator->isValid($address->getStreet()[3])) {
            return [
                'isValid' => false,
                'message' => 'Por favor digite menos de 40 caracteres no campo Bairro.',
            ];
        }

        if (!$this->numberValidator->isValid($address->getStreet()[1])) {
            return [
                'isValid' => false,
                'message' => 'Por favor digite menos de 10 caracteres no campo Número.',
            ];
        }

        if (!$this->streetValidator->isValid($address->getStreet()[0])) {
            return [
                'isValid' => false,
                'message' => 'Por favor digite menos de 60 caracteres no campo Endereço.',
            ];
        }

        return ['isValid' => true];
    }
}
