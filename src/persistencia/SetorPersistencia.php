<?php

namespace App\Persistencia;

use App\Model\Setor;

class SetorPersistencia extends Persistencia
{

    const TABELA = 'setor';

    public function __construct($model)
    {
        parent::__construct(self::TABELA, $model);
    }

    public function delete($id): bool
    {
        return parent::delete([
            'id_setor' => $id
        ]);
    }

    public function findById($id): ?Setor
    {
        $setor = parent::findById([
            'id_setor' => $id
        ]);

        if (!$setor)
            return null;

        return new Setor(
            intval($setor['id_setor']),
            $setor['nome'],
            $setor['ativo'] === 't'
        );
    }

    public function findAll(array $join = [], array $condicao = [], ?int $limit = null, array $order = []): array
    {
        $return = parent::findAll($join, $condicao, $limit, $order);
        $setores = [];
        foreach ($return as $setor) {
            $setores[] = new Setor($setor['id_setor'], $setor['nome'], $setor['ativo'] == 't');
        }
        return $setores;
    }
}
