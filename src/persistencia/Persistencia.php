<?php

namespace App\Persistencia;

use App\Database;
use App\Model\Model;

abstract class Persistencia
{

    protected string $tabela;
    protected $model;

    public function __construct(string $tabela, $model = null)
    {
        $this->tabela = $tabela;
        $this->model = $model;
    }

    public function insert(): bool
    {
        return Database::insert(
            $this->tabela,
            $this->model->getDadosFormatadosBd()
        );
    }

    public function update(array $condicao): bool
    {
        return Database::update(
            $this->tabela,
            $this->model->getDadosFormatadosBd(),
            $condicao
        );
    }

    public function delete(array $condicao): bool
    {
        return Database::delete(
            $this->tabela,
            $condicao
        );
    }

    public function findById(array $condicao)
    {
        $result = Database::select(
            ['*'],
            $this->tabela,
            [],
            $condicao,
            1
        );

        if (isEmpty($result)) {
            return null;
        }

        return $result[0];
    }

    public function findAll(array $join = [], array $condicao = [], ?int $limit = null, array $order = []): array
    {
        $result = Database::select(['*'], $this->tabela, $join, $condicao, $limit, $order);
        return $result;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel( $model): static
    {
        $this->model = $model;
        return $this;
    }

}