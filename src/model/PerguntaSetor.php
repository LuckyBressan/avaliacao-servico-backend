<?php

namespace App\Model;

class PerguntaSetor extends Model
{
    private int $idPergunta;
    private int $idSetor;

    public function __construct(int $idPergunta = 0, int $idSetor = 0)
    {
        $this->idPergunta = $idPergunta;
        $this->idSetor = $idSetor;
    }

    public function getIdPergunta(): int
    {
        return $this->idPergunta;
    }

    public function setIdPergunta(int $idPergunta): static
    {
        $this->idPergunta = $idPergunta;
        return $this;
    }

    public function getIdSetor(): int
    {
        return $this->idSetor;
    }

    public function setIdSetor(int $idSetor): static
    {
        $this->idSetor = $idSetor;
        return $this;
    }

    public function getDadosFormatadosBd(): array
    {
        return [
            'id_pergunta' => $this->idPergunta,
            'id_setor' => $this->idSetor
        ];
    }

    public function getDadosFormatadosJson(): array
    {
        return [
            'idPergunta' => $this->idPergunta,
            'idSetor' => $this->idSetor
        ];
    }
}
