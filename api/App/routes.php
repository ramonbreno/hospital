<?php 

$app->get('/','App\Action\HomeAction:index');
$app->get('/{id}/excluir','App\Action\HomeAction:excluirPaciente');
$app->post('/cadastrar','App\Action\HomeAction:cadastrarPaciente');
$app->post('/editar','App\Action\HomeAction:editarPaciente');
$app->get('/{cpf}/consultar','App\Action\HomeAction:consultarCpfPaciente');

?>