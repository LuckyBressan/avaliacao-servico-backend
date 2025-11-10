<?php

namespace App\Controller;

use App\Persistencia\AvaliacaoPersistencia;
use App\Persistencia\DispositivoPersistencia;
use App\Persistencia\PerguntaSetorPersistencia;
use App\Model\Avaliacao;

class AvaliacaoController
{

    public function concluirAvaliacao()
    {
        try {
            $dados = getDadosPostJson()['avaliacao'] ?? null;
            $dipositivo = $_GET['dispositivo'] ?? null;
            if ($dados && $dipositivo) {
                $avaliacao = new Avaliacao(idDispositivo: (int) $dipositivo);
                $persistencia = new AvaliacaoPersistencia($avaliacao);

                if ($persistencia->insert()) {
                    $resposta = new RespostaAvaliacaoController();
                    $avaliacao = $persistencia->findAll(condicao: [
                        'id_dispositivo = ' . $avaliacao->getIdDispositivo()
                    ], order: [
                        'id_avaliacao' => 'DESC'
                    ], limit: 1)[0];
                    $resposta->salvarRespostasAvaliacao($avaliacao->getIdAvaliacao(), $dados);
                    echo json_encode([
                        'code' => 200
                    ]);
                }
                return;
            } else {
                echo json_encode([
                    'code' => 403,
                    'message' => 'Dados da avaliação ou código do dispositivo não foram informados corretamente'
                ]);
            }
        } catch (\Throwable $th) {
            echo json_encode([
                'code' => $th->getCode() ?: 500,
                'message' => $th->getMessage()
            ]);
            throw $th;
        }
    }

    public function getPerguntasAvaliacao()
    {
        try {
            if (isset($_GET['dispositivo'])) {
                $dispositivo = (int) $_GET['dispositivo'];

                if (is_int($dispositivo)) {
                    $dispositivoPersistencia = new DispositivoPersistencia();
                    $modelDispositivo = $dispositivoPersistencia->findById($dispositivo);
                    if ($modelDispositivo) {
                        $pergSetorPers = new PerguntaSetorPersistencia();
                        $perguntas = $pergSetorPers->findAllPerguntasToAvaliacao($modelDispositivo->getIdSetor());
                        $json = [];
                        foreach ($perguntas as $pergunta) {
                            $json[] = $pergunta->getDadosFormatadosJson();
                        }
                        echo json_encode($json);
                        return;
                    }
                }
            }
            echo json_encode([
                'code' => 403,
                'message' => 'Código do dispositivo não foi informado corretamente'
            ]);
        } catch (\Throwable $th) {
            echo json_encode([
                'code' => $th->getCode() ?: 500,
                'message' => $th->getMessage()
            ]);
            throw $th;
        }
    }

}