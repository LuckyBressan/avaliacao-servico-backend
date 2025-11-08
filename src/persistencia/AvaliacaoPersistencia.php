<?php

require_once('Persistencia.php');

class AvaliacaoPersistencia extends Persistencia
{

    const TABELA = 'avaliacao';

    public function __construct($model)
    {
        parent::__construct(self::TABELA, $model);
    }

    public function delete($id): bool
    {
        return parent::delete([
            'id_avaliacao' => $id
        ]);
    }

    public function findById($id): ?Avaliacao
    {
        $avaliacao = parent::findById([
            'id_avaliacao' => $id
        ]);

        if (!$avaliacao)
            return null;

        return new Avaliacao(
            intval($avaliacao['id_avaliacao']),
            intval($avaliacao['id_dispositivo']),
            $avaliacao['data_hora_avaliacao'] ?? ''
        );
    }

    public function findAll(array $condicao = [], ?int $limit = null, array $order = []): array
    {
        $result = parent::findAll($condicao, $limit, $order);

        $itens = [];
        foreach ($result as $row) {
            $itens[] = new Avaliacao(
                intval($row['id_avaliacao']),
                intval($row['id_dispositivo']),
                $row['data_hora_avaliacao'] ?? ''
            );
        }

        return $itens;
    }
}
