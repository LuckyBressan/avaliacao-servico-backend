<?php

require_once('utils/funcao.php');
require_once('Database.php');

class Pergunta extends Model {

    private ?int $id;
    private string $texto;
    private bool $ativo;

    public function __construct(
        ?int $id = null,
        string $texto = '',
        bool $ativo = true
    ) {
        $this->id = $id;
        $this->texto = $texto;
        $this->ativo = $ativo;
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTexto(): string
    {
        return $this->texto;
    }

    public function isAtivo(): bool
    {
        return $this->ativo;
    }

    // Setters
    public function setTexto(string $texto): static
    {
        $this->texto = $texto;
        return $this;
    }

    public function setAtivo(bool $ativo): static
    {
        $this->ativo = $ativo;
        return $this;
    }

    public function getDadosFormatadosBd(): array {
        return [
            'texto' => $this->texto,
            'ativo' => $this->ativo,
        ];
    }
}