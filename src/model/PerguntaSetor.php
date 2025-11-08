<?php

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

    public function setIdPergunta(int $idPergunta): void
    {
        $this->idPergunta = $idPergunta;
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
        return [
            'id_pergunta' => $this->idPergunta,
            'id_setor' => $this->idSetor
        ];
    }
}
