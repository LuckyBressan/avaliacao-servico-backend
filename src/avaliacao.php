<?php

use App\Controller\AvaliacaoController;

$operacao = isset($_GET['operacao']) ? $_GET['operacao'] : '';

if( !isEmpty($operacao) ) {
    switch (trim(strtoupper($operacao))) {
        case 'CONCLUIR':
            $controller = new AvaliacaoController();
            $controller->concluirAvaliacao();
            break;
        case 'AVALIAR':
            $controller = new AvaliacaoController();
            $controller->getPerguntasAvaliacao();
            break;
        default:
            echo '';
            break;
    }
}
