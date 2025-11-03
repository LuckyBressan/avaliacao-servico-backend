<?php

require_once('utils/funcao.php');
require_once('Database.php');

class Pergunta {

    private ?int $id;
    private string $texto;
    private bool $ativo;
    private bool $feedbackTextual;
    private ?int $idSetor;

    const NOME_TABELA = 'pergunta';

    public function __construct(
        ?int $id = null,
        string $texto = '',
        bool $ativo = true,
        bool $feedbackTextual = false,
        ?int $idSetor = null
    ) {
        $this->id = $id;
        $this->texto = $texto;
        $this->ativo = $ativo;
        $this->feedbackTextual = $feedbackTextual;
        $this->idSetor = $idSetor;
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdSetor(): ?int
    {
        return $this->idSetor;
    }

    public function getTexto(): string
    {
        return $this->texto;
    }

    public function isAtivo(): bool
    {
        return $this->ativo;
    }

    public function hasFeedbackTextual(): bool
    {
        return $this->feedbackTextual;
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

    public function setFeedbackTextual(bool $feedbackTextual): static
    {
        $this->feedbackTextual = $feedbackTextual;
        return $this;
    }

    public function setIdSetor(int $idSetor): static
    {
        $this->idSetor = $idSetor;
        return $this;
    }

    // Métodos de persistência
    public function save(): bool
    {
        if ($this->id === null) {
            return $this->insert();
        }
        return $this->update();
    }

    private function getDadosFormatadosBd() {
        return [
            'texto' => $this->texto,
            'ativo'          => $this->ativo,
            'feedback_textual' => $this->feedbackTextual,
            'id_setor' => $this->idSetor
        ];
    }

    private function insert(): bool
    {
        $dados = $this->getDadosFormatadosBd();

        return Database::insert(self::NOME_TABELA, $dados);
    }

    private function update(): bool
    {
        $dados = $this->getDadosFormatadosBd();

        $condicao = ['id_pergunta = ' . $this->id];

        return Database::update(self::NOME_TABELA, $dados, $condicao);
    }

    public function delete(): bool
    {
        if ($this->id === null) {
            return false;
        }

        $condicao = ['id_pergunta = ' . $this->id];
        return Database::delete(self::NOME_TABELA, $condicao);
    }

    // Métodos estáticos
    public static function findById(int $id): ?Pergunta
    {
        $condicao = ['id_pergunta = ' . $id];
        $result = Database::select(['*'], self::NOME_TABELA, $condicao, 1);

        if (isEmpty($result)) {
            return null;
        }

        $pergunta = $result[0];
        return new Pergunta(
            intval($pergunta['id_pergunta']),
            $pergunta['texto_pergunta'],
            $pergunta['ativo'] === 't',
            $pergunta['feedback_textual'] === 't'
        );
    }

    public static function findAll(bool $apenasAtivas = false): array
    {
        $condicao = $apenasAtivas ? ['ativo = true'] : [];
        $result = Database::select(['*'], self::NOME_TABELA, $condicao);

        $perguntas = [];
        foreach ($result as $pergunta) {
            $perguntas[] = new Pergunta(
                intval($pergunta['id_pergunta']),
                $pergunta['texto_pergunta'],
                $pergunta['ativo'] === 't',
                $pergunta['feedback_textual'] === 't'
            );
        }

        return $perguntas;
    }
}