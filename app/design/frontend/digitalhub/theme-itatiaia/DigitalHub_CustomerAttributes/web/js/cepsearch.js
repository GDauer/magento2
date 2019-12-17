require([
    'jquery',
    'DigitalHub_CustomerAttributes/js/jquery.mask.min'
], function ($) {

    // Limpa valores do formulário de cep.
    function limpaFormularioCep() {
        $("input[name='street[0]']").val();
        $("input[name='street[3]']").val();
        $("input[name='city']").val();
        $('select[name="region_id"]').attr('disabled',false);
        $('input[name="street[3]"]').attr('disabled',false);
        $('input[name="city"]').attr('disabled',false);
        $('select[name="country_id"]').attr('disabled',false);
    }

    // Executa busca de CEP
    $(document).on("blur", "input[name='postcode']", function () {
        var me = $(this);
        var closestForm = $(this).closest('.form');
        console.log(closestForm);

        var v = document.querySelectorAll('input[name$="postcode"]');

        //mask postcode
        $('input[name=postcode]').mask('00000-000', {
            placeholder: "00000-000",
        });

        // mask phone
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

        //Nova variável "cep" somente com dígitos.
        if (v[1].value !== '') {
            var cep = v[1].value.replace(/\D/g, '');
        } else {
            var cep = v[0].value.replace(/\D/g, '');
        }

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            // Matris estado
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

            //Valida o formato do CEP.
            if (validacep.test(cep)) {
                //Preenche os campos com "..." enquanto consulta webservice.
                closestForm.find("input[name='street[0]']").val('');
                closestForm.find("input[name='street[3]']").val('');
                closestForm.find("input[name='city']").val('');

                //Consulta o webservice viacep.com.br/
                $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {
                    var ufm;

                    if (!("erro" in dados)) {
                        console.log(closestForm);
                        //Atualiza os campos com os valores da consulta.
                        closestForm.find("input[name='street[0]']").val(dados.logradouro).trigger('change');
                        closestForm.find("input[name='street[3]']").val(dados.bairro).trigger('change');
                        closestForm.find("input[name='city']").val(dados.localidade).trigger('change');

                        for (var i = 0; i < 27; i++) {
                            ufm = estado.UF[i].sigla;

                            if (ufm == dados.uf) {
                                $("select[name='region_id'] option[value=" + estado.UF[i].id + "]").attr('selected', 'selected').trigger('change');
                            }
                        }
                        $('select[name="region_id"]').attr('disabled',true);
                        $('input[name="street[3]"]').attr('disabled',true);
                        $('input[name="city"]').attr('disabled',true);
                        $('select[name="country_id"]').attr('disabled',true);
                    } //end if.
                    else {
                        //CEP pesquisado não foi encontrado.
                        limpaFormularioCep();
                        // alert("CEP não encontrado.");
                        $("#co-shipping-form").submit(function (event) {
                            console.log('error');
                            event.preventDefault();
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
})
