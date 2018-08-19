<?php
namespace App\Action;

class HomeAction extends Action{
	
	public function index($request, $response){

	    $sql = "SELECT * FROM paciente ORDER BY id DESC";

        $paciente = $this->db->prepare($sql);
        $paciente->execute();
        $meuArray = "";
        $stmt = $paciente->fetchAll(\PDO::FETCH_OBJ);

        foreach ($stmt as $key=>$value){
            $data = $value->data_nasc;
            $meuArray [] = array('id' => $value->id, 'cpf' => $value->cpf, 'nome' => $value->nome,'data_nasc' => date('d/m/Y', strtotime($data)));
        }

	    echo json_encode(array('data' => $meuArray));
	}
    public function excluirPaciente($request, $response){
        $id = $request->getAttribute('id');

        $sql = "SELECT * FROM paciente WHERE id = ?";
        $post = $this->db->prepare($sql);
        $post->execute(array($id));

        $resposta = "falha";
        $message = "Algo de errado aconteceu!";

        if($post->rowCount() > 0){
            $sql = "DELETE FROM paciente WHERE `id` = ?";
            $deletar = $this->db->prepare($sql);

            if($deletar->execute(array($id))){
                $resposta = "sucesso";
                $message = "Paciente excluído com sucesso!";
            }
        }

        echo json_encode(array('resposta' => $resposta, 'message' => $message));
    }
    public function cadastrarPaciente($request, $response){
        $data = $request->getParsedBody();

        $cpf = filter_var($data['cpf_paciente'],FILTER_SANITIZE_STRING);
        $nome = filter_var($data['nome_paciente'],FILTER_SANITIZE_STRING);
        $datanasc = date('Y-m-d', strtotime(filter_var($data['datanasc_paciente'],FILTER_SANITIZE_STRING)));

        $sql = "INSERT INTO paciente SET cpf = ?, nome = ?, data_nasc = ?";
        $cadastrar = $this->db->prepare($sql);

        $resposta = "falha";
        $message = "Algo de errado aconteceu!";

        if($cadastrar->execute(array($cpf,$nome,$datanasc))){
            $resposta = "sucesso";
            $message = "Paciente cadastrado com sucesso!";
        }


        echo json_encode(array('resposta' => $resposta, 'message' => $message));
    }
    public function editarPaciente($request, $response){

        $data = $request->getParsedBody();

        $id = filter_var($data['id_paciente'],FILTER_SANITIZE_STRING);
        $cpf = filter_var($data['cpf_paciente'],FILTER_SANITIZE_STRING);
        $nome = filter_var($data['nome_paciente'],FILTER_SANITIZE_STRING);
        $datanasc = date('Y-m-d', strtotime(filter_var($data['datanasc_paciente'],FILTER_SANITIZE_STRING)));

        $sql = "UPDATE paciente SET cpf = ?, nome = ?, data_nasc = ? WHERE id = ?";
        $editar = $this->db->prepare($sql);

        $resposta = "falha";
        $message = "Algo de errado aconteceu!";

        if($editar->execute(array($cpf, $nome, $datanasc, $id))){
            $resposta = "sucesso";
            $message = "Paciente atualizado com sucesso!";
        }

        echo json_encode(array('resposta' => $resposta, 'message' => $message));
    }
    public function consultarCpfPaciente($request, $response){
        $cpf = $request->getAttribute('cpf');

        $sql = "SELECT * FROM paciente WHERE cpf = ?";
        $consultar = $this->db->prepare($sql);
        $consultar->execute(array($cpf));

        $resposta = "falha";
        $message = "Algo de errado aconteceu!";

        if($consultar->rowCount() > 0){
            $resposta = "existe";
            $message = "CPF existente!";
        }

        echo json_encode(array('resposta' => $resposta, 'message' => $message));
    }
}

?>