<?php

require_once('Persistencia.php');

class PerguntaPersistencia extends Persistencia {

    const TABELA = 'pergunta';

    public function __construct(Pergunta $model) {
        parent::__construct(self::TABELA, $model);
    }

    public function delete($id): bool {
        return parent::delete([
            'id_pergunta' => $id
        ]);
    }

    public function findById($id): ?Pergunta {
        $pergunta = parent::findById([
            'id_pergunta' => $id
        ]);

        if( !$pergunta ) return null;

        return new Pergunta(
            intval($pergunta['id_pergunta']),
            $pergunta['texto'],
            $pergunta['ativo'] === 't'
        );
    }

    public function findAll(array $condicao = [], ?int $limit = null, array $order = []): array
    {
        $result = parent::findAll($condicao, $limit, $order);

        $perguntas = [];
        foreach ($result as $pergunta) {
            $perguntas[] = new Pergunta(
                intval($pergunta['id_pergunta']),
                $pergunta['texto_pergunta'],
                $pergunta['ativo'] === 't'
            );
        }

        return $perguntas;
    }
}
