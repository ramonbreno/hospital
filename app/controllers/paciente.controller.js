/**
 * Created by Ramon Breno on 21/05/2018.
 */
angular.module('paciente').controller('PacienteController', PacienteController);

function PacienteController($http) {

    let tc = this;
    tc.id = 0;
    tc.consultaCpf = '';
    tc.cpfCompare = '';
    tc.messageCarregamento = '';
    
    let crud = this;
    
    crud.carregarDados = function(){
        $http({
            method: 'GET',
            url: 'api',
        }).then(function (dados) {

            try {
                tc.valores = dados['data']['data'];
                if(tc.valores.length == 0)
                    tc.messageCarregamento = 'Nenhum paciente cadastrado!';
                else
                    tc.messageCarregamento = '';
            }
            catch (excecao) {
                alert('Opa! Ocorreu algum erro inesperado.');
            }
        }, function () {
            alert('Opa! Não foi possivel processar.');
        }).finally(function () {

        });
    }

    crud.atualizarID = function ($id) {
        tc.id = $id;
    }
    crud.carregarFormEdit = function($id, $cpf, $nome, $dataNasc, $acao){
        tc.acao = $acao;
        tc.id = $id;
        tc.cpf = $cpf;
        tc.nome = $nome;
        let arrayDate = $dataNasc.split('/');
        tc.datanasc = new Date(arrayDate[1] + '/' +arrayDate[0] + '/' + arrayDate[2]);

        if($acao === 'editar') {
            tc.cpfCompare = tc.cpf;
            tc.cpf = $cpf.match(/.{1,3}/g).join(".").replace(/\.(?=[^.]*$)/,"-");
        }else
            tc.cpfCompare = '';
            tc.consultaCpf = '';

    }
    crud.enviarFormularioPaciente = function () {
        let cpf_replace = tc.cpf.replace(/[\.-]/g,'');
        let route = '';

        if(tc.acao == 'cadastrar')
            route = 'cadastrar';
        else
            route = 'editar';

        if(tc.consultaCpf == 'CPF existente!'){
            alert('Número de CPF já existente');
            return false;
        }else if(tc.datanasc.getFullYear() < 1970 || tc.datanasc > new Date()){
            alert('Digite uma data válida');
            return false;
        }


        $http({
            method: 'POST',
            url: 'api/'+route,
            data: {
                id_paciente: tc.id,
                cpf_paciente: cpf_replace,
                nome_paciente: tc.nome,
                datanasc_paciente: tc.datanasc
            }
        }).then(function (dados) {

            try {
                alert(dados['data']['message']);

                if(dados['data']['resposta'] == "sucesso"){
                    $('#fecharCadastro').click();
                    crud.carregarDados();
                }
            }
            catch (excecao) {
                alert('Opa! Ocorreu algum erro inesperado.');
            }
        }, function () {
            alert('Opa! Não foi possivel processar.');
        }).finally(function () {

        });
    }
    crud.excluirPaciente = function () {
        $http({
            method: 'GET',
            url: 'api/'+tc.id+'/excluir',
            data: {
                id: tc.id,
            }
        }).then(function (dados) {

            try {
                alert(dados['data']['message']);

                if(dados['data']['resposta'] == "sucesso")
                    document.location.href = '/hospital';
            }
            catch (excecao) {
                alert('Opa! Ocorreu algum erro inesperado.');
            }
        }, function () {
            alert('Opa! Não foi possivel processar.');
        }).finally(function () {

        });
    }
    crud.consultarCpf = function () {

        let cpf_replace = tc.cpf.replace(/[\.-]/g,'');

        tc.consultaCpf = '';

        $http({
            method: 'GET',
            url: 'api/'+cpf_replace+'/consultar',
        }).then(function (dados) {

            try {
                if(dados['data']['resposta'] == "existe")
                    tc.consultaCpf = dados['data']['message'];
                if(tc.cpfCompare.length > 0 && tc.cpfCompare === cpf_replace)
                    tc.consultaCpf = '';
            }
            catch (excecao) {
                alert('Opa! Ocorreu algum erro inesperado.');
            }
        }, function () {
            //alert('Opa! Não foi possivel processar.');
        }).finally(function () {

        });

    }
    crud.carregarDados();
}