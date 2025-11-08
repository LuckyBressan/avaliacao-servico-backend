<?php

class RespostaAvaliacao
{
    private int $idAvaliacao;
    private int $idPergunta;
    private int $resposta;
    private ?string $feedbackTextual;

    public function __construct(int $idAvaliacao = 0, int $idPergunta = 0, int $resposta = 0, ?string $feedbackTextual = null)
    {
        $this->idAvaliacao = $idAvaliacao;
        $this->idPergunta = $idPergunta;
        $this->resposta = $resposta;
        $this->feedbackTextual = $feedbackTextual;
    }

    public function getIdAvaliacao(): int
    {
        return $this->idAvaliacao;
    }

    public function setIdAvaliacao(int $idAvaliacao): void
    {
        $this->idAvaliacao = $idAvaliacao;
    }

    public function getIdPergunta(): int
    {
        return $this->idPergunta;
    }

    public function setIdPergunta(int $idPergunta): void
    {
        $this->idPergunta = $idPergunta;
    }

    public function getResposta(): int
    {
        return $this->resposta;
    }

    public function setResposta(int $resposta): void
    {
        if ($resposta < 1 || $resposta > 10) {
            throw new InvalidArgumentException("A resposta deve estar entre 1 e 10.");
        }
        $this->resposta = $resposta;
    }

    public function getFeedbackTextual(): ?string
    {
        return $this->feedbackTextual;
    }

    public function setFeedbackTextual(?string $feedbackTextual): void
    {
        $this->feedbackTextual = $feedbackTextual;
    }

    public function getDadosFormatadosBd(): array
    {
        return [
            'id_avaliacao' => $this->idAvaliacao,
            'id_pergunta' => $this->idPergunta,
            'resposta' => $this->resposta,
            'feedback_textual' => $this->feedbackTextual
        ];
    }
}
