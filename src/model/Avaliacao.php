<?php

namespace App\Model;

class Avaliacao
{
    private ?int $idAvaliacao;
    private int $idDispositivo;
    private string $dataHoraAvaliacao;

    public function __construct(?int $idAvaliacao = null, int $idDispositivo = 0, string $dataHoraAvaliacao = '')
    {
        $this->idAvaliacao = $idAvaliacao;
        $this->idDispositivo = $idDispositivo;
        $this->dataHoraAvaliacao = $dataHoraAvaliacao ?: date('Y-m-d H:i:s');
    }

    public function getIdAvaliacao(): int
    {
        return $this->idAvaliacao;
    }

    public function setIdAvaliacao(int $idAvaliacao): void
    {
        $this->idAvaliacao = $idAvaliacao;
    }

    public function getIdDispositivo(): int
    {
        return $this->idDispositivo;
    }

    public function setIdDispositivo(int $idDispositivo): void
    {
        $this->idDispositivo = $idDispositivo;
    }

    public function getDataHoraAvaliacao(): string
    {
        return $this->dataHoraAvaliacao;
    }

    public function setDataHoraAvaliacao(string $dataHoraAvaliacao): void
    {
        $this->dataHoraAvaliacao = $dataHoraAvaliacao;
    }

    public function getDadosFormatadosBd(): array
    {
        return array_merge(
            [
            'id_dispositivo' => $this->idDispositivo,
            'data_hora_avaliacao' => $this->dataHoraAvaliacao
            ],
            isset($this->idAvaliacao)
                ? [
                    'id_avaliacao' => $this->idAvaliacao,
                ]
                : []
        );
    }

    public function getDadosFormatadosJson(): array
    {
        return [
            'idAvaliacao' => $this->idAvaliacao,
            'idDispositivo' => $this->idDispositivo,
            'dataHoraAvaliacao' => $this->dataHoraAvaliacao
        ];
    }
}
