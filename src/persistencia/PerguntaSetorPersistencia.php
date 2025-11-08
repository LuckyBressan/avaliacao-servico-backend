<?php

require_once('Persistencia.php');

class PerguntaSetorPersistencia extends Persistencia
{

    const TABELA = 'pergunta_setor';

    public function __construct($model)
    {
        parent::__construct(self::TABELA, $model);
    }

    public function delete($idPergunta, $idSetor = null): bool
    {
        // if only idPergunta provided, delete by pergunta; if both provided, use both
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

    public function findById($idPergunta, $idSetor = null)
    {
        $cond = ['id_pergunta' => $idPergunta];
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

    public function findAll(array $condicao = [], ?int $limit = null, array $order = []): array
    {
        $result = parent::findAll($condicao, $limit, $order);

        $itens = [];
        foreach ($result as $row) {
            $itens[] = new PerguntaSetor(
                intval($row['id_pergunta']),
                intval($row['id_setor'])
            );
        }

        return $itens;
    }
}
