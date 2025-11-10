<?php

namespace App\Model;

abstract class Model
{

    public function getDadosFormatadosBd(): array
    {
        return [];
    }

    public function getDadosFormatadosJson(): array
    {
        return [];
    }

}