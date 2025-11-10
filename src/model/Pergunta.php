<?php

namespace App\Model;

require_once(__DIR__ . '/../utils/funcao.php');

use App\Database;

class Pergunta extends Model
{

    private ?int $id;
    private string $texto;
    private bool $ativo;
    private array $setor;

    public function __construct(
        ?int $id = null,
        string $texto = '',
        bool $ativo = true
    ) {
        $this->id = $id;
        $this->texto = $texto;
        $this->ativo = $ativo;
    }

    public function adicionaSetor(?int $id = null, string $nome = '', bool $ativo = true): static
    {
        $setor = new Setor($id, $nome, $ativo);
        $this->setor[] = $setor;
        return $this;
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

    public function getSetor(): array
    {
        return $this->setor;
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

    public function setSetor(array $setor = []): static
    {
        $this->setor = $setor;
        return $this;
    }

    public function getDadosFormatadosBd(): array
    {
        return array_merge(
            [
                'texto' => $this->texto,
                'ativo' => $this->ativo,
            ],
            isset($this->id) ? ['id_pergunta' => $this->id] : []
        );
    }

    public function getDadosFormatadosJson(): array
    {
        $setoresJson = [];
        if (isset($this->setor)) {
            foreach ($this->setor as $s) {
                if (is_object($s) && method_exists($s, 'getDadosFormatadosJson')) {
                    $setoresJson[] = $s->getDadosFormatadosJson();
                } else {
                    $setoresJson[] = $s;
                }
            }
        }

        return [
            'id' => $this->id,
            'texto' => $this->texto,
            'ativo' => $this->ativo,
            'setor' => $setoresJson
        ];
    }
}