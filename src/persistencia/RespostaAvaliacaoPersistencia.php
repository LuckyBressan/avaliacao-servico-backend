<?php

namespace App\Persistencia;

use App\Model\RespostaAvaliacao;

class RespostaAvaliacaoPersistencia extends Persistencia
{

    const TABELA = 'resposta_avaliacao';

    public function __construct($model)
    {
        parent::__construct(self::TABELA, $model);
    }

    public function delete($idAvaliacao, $idPergunta = null): bool
    {
        if ($idPergunta === null) {
            return parent::delete([
                'id_avaliacao' => $idAvaliacao
            ]);
        }

        return parent::delete([
            'id_avaliacao' => $idAvaliacao,
            'id_pergunta' => $idPergunta
        ]);
    }

    public function findById($idAvaliacao, $idPergunta = null)
    {
        $cond = ['id_avaliacao' => $idAvaliacao];
        if ($idPergunta !== null)
            $cond['id_pergunta'] = $idPergunta;

        $row = parent::findById($cond);
        if (!$row)
            return null;

        return new RespostaAvaliacao(
            intval($row['id_avaliacao']),
            intval($row['id_pergunta']),
            intval($row['resposta']),
            $row['feedback_textual'] ?? null
        );
    }

    public function findAll(array $join = [], array $condicao = [], ?int $limit = null, array $order = []): array
    {
        $result = parent::findAll($join, $condicao, $limit, $order);

        $itens = [];
        foreach ($result as $row) {
            $itens[] = new RespostaAvaliacao(
                intval($row['id_avaliacao']),
                intval($row['id_pergunta']),
                intval($row['resposta']),
                $row['feedback_textual'] ?? null
            );
        }

        return $itens;
    }
}
