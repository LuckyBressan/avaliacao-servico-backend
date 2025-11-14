<?php

namespace App\Controller;

use App\Model\RespostaAvaliacao;
use App\Persistencia\RespostaAvaliacaoPersistencia;

class RespostaAvaliacaoController
{

    public function salvarRespostasAvaliacao(int $idAvaliacao, array $respostas): bool
    {

        if( $idAvaliacao && !isEmpty($respostas) ) {

            $persistencia = new RespostaAvaliacaoPersistencia(null);

            foreach ($respostas as $resposta) {
                $resposta = new RespostaAvaliacao(
                    $idAvaliacao,
                    intval($resposta['idPergunta']),
                    intval($resposta['resposta']),
                    $resposta['feedbackTextual'] ?? null
                );
                $persistencia->setModel($resposta);
                if( !$persistencia->insert() ) {
                    throw new \Exception('Erro ao salvar resposta da avaliação', 500);
                }
            }
            return true;
        }
        return false;
    }

}