<?php

namespace App\Persistencia;

use App\Model\PerguntaSetor;
use App\Model\Pergunta;

class PerguntaSetorPersistencia extends Persistencia
{

    const TABELA = 'pergunta_setor';

    public function __construct($model = null)
    {
        if ($model === null)
            $model = new PerguntaSetor();
        parent::__construct(self::TABELA, $model);
    }

    public function delete($idPergunta, $idSetor = null): bool
    {
        if ($idSetor === null) {
            return parent::delete([
                'id_pergunta' => $idPergunta
            ]);
        }

        return parent::delete([
            'id_pergunta' => $idPergunta,
            'id_setor' => $idSetor
        ]);
    }

    public function findById($idPergunta = null, $idSetor = null)
    {
        //Se ambos forem vazios, retorna nada
        if (!$idPergunta && !$idSetor)
            return null;

        if ($idPergunta !== null) {
            $cond = ['id_pergunta' => $idPergunta];
        }
        if ($idSetor !== null)
            $cond['id_setor'] = $idSetor;

        $row = parent::findById($cond);

        if (!$row)
            return null;

        return new PerguntaSetor(
            intval($row['id_pergunta']),
            intval($row['id_setor'])
        );
    }

    public function findAll(array $join = [], array $condicao = [], ?int $limit = null, array $order = []): array
    {
        $result = parent::findAll($join, $condicao, $limit, $order);

        $itens = [];
        foreach ($result as $row) {
            $itens[] = new PerguntaSetor(
                intval($row['id_pergunta']),
                intval($row['id_setor'])
            );
        }

        return $itens;
    }

    public function findAllPerguntasToAvaliacao(int $idSetor)
    {
        $result = parent::findAll(
            [
                'RIGHT' => [
                    PerguntaPersistencia::TABELA . ' p ',
                    'USING' => '(id_pergunta)'
                ]
            ],
            [
                "pergunta_setor.id_setor = $idSetor",
                'OR' => 'pergunta_setor.id_setor IS NULL'
            ],
            order: ['p.id_pergunta' => 'ASC']
        );

        $perguntas = [];
        foreach ($result as $row) {
            if ($row['ativo'] !== 't')
                continue;
            $pergunta = new Pergunta($row['id_pergunta'], $row['texto']);
            $perguntas[] = $pergunta;
        }

        return $perguntas;
    }
}
