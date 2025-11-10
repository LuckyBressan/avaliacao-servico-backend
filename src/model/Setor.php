<?php

namespace App\Model;

class Setor
{
    private ?int $idSetor;
    private string $nome;
    private bool $ativo;

    public function __construct(?int $idSetor = null, string $nome = '', bool $ativo = true)
    {
        $this->idSetor = $idSetor;
        $this->nome = $nome;
        $this->ativo = $ativo;
    }

    public function getIdSetor(): int
    {
        return $this->idSetor;
    }

    public function setIdSetor(int $id_setor): void
    {
        $this->idSetor = $id_setor;
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

    public function getDadosFormatadosBd(): array
    {
        return array_merge(
            [
                'nome' => $this->nome,
                'ativo' => $this->ativo
            ],
            isset($this->idSetor) ? ['id_setor' => $this->idSetor] : []
        );
    }

    public function getDadosFormatadosJson(): array
    {
        return [
            'idSetor' => $this->idSetor,
            'nome' => $this->nome,
            'ativo' => $this->ativo
        ];
    }
}
