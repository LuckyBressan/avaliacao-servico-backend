<?php

namespace App\Model;

class Dispositivo
{
    private int $idDispositivo;
    private string $nome;
    private bool $ativo;
    private int $idSetor;

    public function __construct(int $idDispositivo = 0, string $nome = '', bool $ativo = true, int $idSetor = 0)
    {
        $this->idDispositivo = $idDispositivo;
        $this->nome = $nome;
        $this->ativo = $ativo;
        $this->idSetor = $idSetor;
    }

    public function getIdDispositivo(): int
    {
        return $this->idDispositivo;
    }

    public function setIdDispositivo(int $idDispositivo): void
    {
        $this->idDispositivo = $idDispositivo;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function isAtivo(): bool
    {
        return $this->ativo;
    }

    public function setAtivo(bool $ativo): void
    {
        $this->ativo = $ativo;
    }

    public function getIdSetor(): int
    {
        return $this->idSetor;
    }

    public function setIdSetor(int $idSetor): void
    {
        $this->idSetor = $idSetor;
    }

    public function getDadosFormatadosBd(): array
    {
        return array_merge(
            [
                'id_setor' => $this->idSetor,
                'nome' => $this->nome,
                'ativo' => $this->ativo
            ],
            $this->idDispositivo > 0 ? ['id_dispositivo' => $this->idDispositivo] : []
        );
    }

    public function getDadosFormatadosJson(): array
    {
        return [
            'idDispositivo' => $this->idDispositivo,
            'idSetor' => $this->idSetor,
            'nome' => $this->nome,
            'ativo' => $this->ativo
        ];
    }
}
