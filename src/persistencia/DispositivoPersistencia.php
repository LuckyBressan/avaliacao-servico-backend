<?php

namespace App\Persistencia;

use App\Model\Dispositivo;

class DispositivoPersistencia extends Persistencia
{

    const TABELA = 'dispositivo';

    public function __construct($model = null)
    {
        if ($model === null) $model = new Dispositivo();
        parent::__construct(self::TABELA, $model);
    }

    public function delete(array $condicao = []): bool
    {
        $condicao = count($condicao) ? $condicao : [
            'id_dispositivo' => $this->model->getIdDispositivo()
        ];
        return parent::delete($condicao);
    }

    public function findById($id): ?Dispositivo
    {
        $dispositivo = parent::findById([
            "id_dispositivo = $id"
        ]);

        if (!$dispositivo)
            return null;

        return new Dispositivo(
            intval($dispositivo['id_dispositivo']),
            $dispositivo['nome'],
            ($dispositivo['ativo'] ?? '') === 't',
            intval($dispositivo['id_setor'] ?? 0)
        );
    }

    public function findAll(array $join = [], array $condicao = [], ?int $limit = null, array $order = []): array
    {
        $result = parent::findAll($join, $condicao, $limit, $order);

        $itens = [];
        foreach ($result as $row) {
            $itens[] = new Dispositivo(
                intval($row['id_dispositivo']),
                $row['nome'] ?? '',
                ($row['ativo'] ?? '') === 't',
                intval($row['id_setor'] ?? 0)
            );
        }

        return $itens;
    }
}
