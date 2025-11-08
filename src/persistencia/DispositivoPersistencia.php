<?php

require_once('Persistencia.php');

class DispositivoPersistencia extends Persistencia
{

    const TABELA = 'dispositivo';

    public function __construct($model)
    {
        parent::__construct(self::TABELA, $model);
    }

    public function delete($id): bool
    {
        return parent::delete([
            'id_dispositivo' => $id
        ]);
    }

    public function findById($id): ?Dispositivo
    {
        $dispositivo = parent::findById([
            'id_dispositivo' => $id
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

    public function findAll(array $condicao = [], ?int $limit = null, array $order = []): array
    {
        $result = parent::findAll($condicao, $limit, $order);

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
