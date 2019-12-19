require([
    'jquery',
    'DigitalHub_CustomerAttributes/js/jquery.mask.min'
], function ($, inputMask) {

    // Verificação do tipo de conta selecionada no form
    function verifyAccountType(val) {
        if ($('input[name=account_type]:checked').val() == 'pf') {
            $('.account-type-pj').hide();
            $('input[name=taxvat]').parents('.field:first').find('label:first').text('CPF');
            $('input[name=taxvat]').val(val);
            $('input[name=taxvat]').mask('000.000.000-00', {
                placeholder: "000.000.000-00",
            });
        } else if ($('input[name=account_type]:checked').val() == 'pj') {
            $('.account-type-pj').show();
            $('input[name=taxvat]').parents('.field:first').find('label:first').text('CNPJ');
            $('input[name=taxvat]').val(val);
            $('input[name=taxvat]').mask('00.000.000/0000-00', {
                placeholder: "00.000.000/0000-00"

            });
        }
    }

    // Limpa valores do formulário de cep.

    function limpaFormularioCep() {
        $("#street_1").val("");
        $("#street_4").val("");
        $("#city").val("");
        $('select[name="region_id"]').attr('readonly',false);
        $('select[name="region_id"]').css("touch-action","");
        $('input[name="street[3]"]').attr('readonly',false);
        $('input[name="city"]').attr('readonly',false);
        $('select[name="country_id"]').attr('readonly',false);
        $('select[name="country_id"]').css("touch-action","");
    }

    // Init
    $(function () {
        var TelephoneBehaviour = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        };
        var telOptions = {
            onKeyPress: function (val, e, field, options) {
                field.mask(TelephoneBehaviour.apply({}, arguments), options);
            },
            placeholder: "(00) 00000-0000"
        };
        $('input[name=telephone]').mask(TelephoneBehaviour, telOptions);

        // $('input[name=taxvat]').mask('000.000.000-00', {
        //     placeholder: "000.000.000-00",
        // });

        $('input[name=postcode]').mask('00000-000', {
            placeholder: "00000-000",
        });

        $('.account-type-pj').hide();
        var val = $('input[name=taxvat]').val();
        $('input[name=taxvat]').parents('.field:first').find('label:first').text('CPF');
        $('input[name=account_type]').change(function () {
            verifyAccountType(val);
        });
        verifyAccountType(val);
    })

    $('.ceperror').css("display", "none");
    $('.ceperror').css("font-size", "13px");

    var estado = {
        UF: [
            {"nome": "Acre", "sigla": "AC", "id": 485},
            {"nome": "Alagoas", "sigla": "AL", "id": 486},
            {"nome": "Amapá", "sigla": "AP", "id": 487},
            {"nome": "Amazonas", "sigla": "AM", "id": 488},
            {"nome": "Bahia", "sigla": "BA", "id": 489},
            {"nome": "Ceará", "sigla": "CE", "id": 490},
            {"nome": "Distrito Federal", "sigla": "DF", "id": 511},
            {"nome": "Espírito Santo", "sigla": "ES", "id": 491},
            {"nome": "Goiás", "sigla": "GO", "id": 492},
            {"nome": "Maranhão", "sigla": "MA", "id": 493},
            {"nome": "Mato Grosso", "sigla": "MT", "id": 494},
            {"nome": "Mato Grosso do Sul", "sigla": "MS", "id": 495},
            {"nome": "Minas Gerais", "sigla": "MG", "id": 496},
            {"nome": "Pará", "sigla": "PA", "id": 497},
            {"nome": "Paraíba", "sigla": "PB", "id": 498},
            {"nome": "Paraná", "sigla": "PR", "id": 499},
            {"nome": "Pernambuco", "sigla": "PE", "id": 500},
            {"nome": "Piauí", "sigla": "PI", "id": 501},
            {"nome": "Rio de Janeiro", "sigla": "RJ", "id": 502},
            {"nome": "Rio Grande do Norte", "sigla": "RN", "id": 503},
            {"nome": "Rio Grande do Sul", "sigla": "RS", "id": 504},
            {"nome": "Rondônia", "sigla": "RO", "id": 505},
            {"nome": "Roraima", "sigla": "RR", "id": 506},
            {"nome": "Santa Catarina", "sigla": "SC", "id": 507},
            {"nome": "São Paulo", "sigla": "SP", "id": 508},
            {"nome": "Sergipe", "sigla": "SE", "id": 509},
            {"nome": "Tocantins", "sigla": "TO", "id": 510}
        ]
    };


    //Quando o campo cep perde o foco.
    $("#zip").blur(function () {


        //Nova variável "cep" somente com dígitos.
        var cep = $("#zip").val().replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if (validacep.test(cep)) {

                $("#street_1").val('');
                $("#street_4").val('');
                $("#city").val('');

                //Consulta o webservice viacep.com.br/
                $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {
                    var ufm;

                    if (!("erro" in dados)) {
                        //Atualiza os campos com os valores da consulta.
                        $("#street_1").val(dados.logradouro).trigger('change');
                        $("#street_4").val(dados.bairro).trigger('change');
                        $("#city").val(dados.localidade).trigger('change');

                        for (var i = 0; i < 27; i++) {
                            ufm = estado.UF[i].sigla;

                            if (ufm == dados.uf) {
                                $("select[name='region_id'] option[value=" + estado.UF[i].id + "]").attr('selected', 'selected').trigger('change');
                            }
                        }
                        $('select[name="region_id"]').attr('readonly',true);
                        $('select[name="region_id"]').css("touch-action","none");
                        $('input[name="street[3]"]').attr('readonly',true);
                        $('input[name="city"]').attr('readonly',true);
                        $('select[name="country_id"]').attr('readonly',true);
                        $('select[name="country_id"]').css("touch-action","none");
                        //$('.ceperror').css("display", "none");
                        //$('.ceperror').css("color", "transparent");

                    } //end if.
                    else {
                        //CEP pesquisado não foi encontrado.
                        limpaFormularioCep();
                        // alert("CEP não encontrado.");
                        //$('.ceperror').css("display", "block");
                        //$('.ceperror').css("color", "red");
                        $(".form-address-edit").submit(function (event) {
                            event.preventDefault();
                            return false;
                        });

                    }
                });
            } //end if.
            else {
                //cep é inválido.
                limpaFormularioCep();
                alert("Formato de CEP inválido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpaFormularioCep();
        }
    });
});
