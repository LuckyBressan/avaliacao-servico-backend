<?php

namespace App\Persistencia;

use App\Model\Pergunta;

class PerguntaPersistencia extends Persistencia {

    const TABELA = 'pergunta';

    public function __construct($model = new Pergunta()) {
        parent::__construct(self::TABELA, $model);
    }

    public function insert(): bool
    {
        $return = parent::insert();
        if( count($this->model->getSetor()) ) {
            /**
             * @var \App\Model\Setor[]
             */
            $setores = $this->model->getSetor();
            $pergSetorPers = new PerguntaSetorPersistencia();
            /**
             * @var \App\Model\PerguntaSetor
             */
            $pergSetorModel = $pergSetorPers->getModel();
            foreach ($setores as $setor) {
                $pergSetorModel
                    ->setIdPergunta($this->model->getId())
                    ->setIdSetor($setor->getIdSetor());
                $pergSetorPers->insert();
            }
        }
        return $return;
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

    public function findAll(array $join = [], array $condicao = [], ?int $limit = null, array $order = []): array
    {
        $result = parent::findAll($join, $condicao, $limit, $order);

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
