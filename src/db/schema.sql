DROP TABLE IF EXISTS resposta_avaliacao;
DROP TABLE IF EXISTS PERGUNTA;
DROP TABLE IF EXISTS AVALIACAO;
DROP TABLE IF EXISTS DISPOSITIVO;
DROP TABLE IF EXISTS SETOR;

CREATE TABLE setor (
	id_setor SERIAL PRIMARY KEY,
	nome TEXT NOT NULL,
	ativo BOOLEAN NOT NULL DEFAULT TRUE
);

COMMENT ON TABLE setor IS 'Tabela que armazena os setores do estabelecimento';
COMMENT ON COLUMN setor.id_setor IS 'Identificador único do setor';
COMMENT ON COLUMN setor.nome IS 'Nome completo do setor';
COMMENT ON COLUMN setor.ativo IS 'Define se o setor está ativo (TRUE) ou inativo (FALSE)';

CREATE TABLE dispositivo (
	id_dispositivo SERIAL PRIMARY KEY,
	nome TEXT NOT NULL,
	ativo BOOLEAN NOT NULL DEFAULT TRUE,
	id_setor INT NOT NULL,
    CONSTRAINT fk_dispositivo_setor
        FOREIGN KEY (id_setor)
        REFERENCES setor (id_setor)
        ON UPDATE CASCADE
        ON DELETE RESTRICT

);

COMMENT ON TABLE dispositivo IS 'Tabela que armazena os dispositivos a partir de onde serão realizadas as avaliações';
COMMENT ON COLUMN dispositivo.id_dispositivo IS 'Identificador único do setor';
COMMENT ON COLUMN dispositivo.nome IS 'Nome do dispositivo';
COMMENT ON COLUMN dispositivo.ativo IS 'Define se o dispositivo está ativo (TRUE) ou inativo (FALSE)';
COMMENT ON COLUMN dispositivo.id_setor IS 'Identificador do setor vinculado ao dispositivo';

-- Criação da tabela "pergunta"
CREATE TABLE pergunta (
    id_pergunta SERIAL PRIMARY KEY,         -- Identificador único (PK)
    texto TEXT NOT NULL,           -- Texto longo da pergunta
    ativo BOOLEAN NOT NULL DEFAULT TRUE,    -- Indica se a pergunta está ativa (TRUE/FALSE)
);

-- Comentários opcionais para documentação
COMMENT ON TABLE pergunta IS 'Tabela que armazena as perguntas do sistema.';
COMMENT ON COLUMN pergunta.id_pergunta IS 'Identificador único da pergunta.';
COMMENT ON COLUMN pergunta.texto IS 'Texto completo da pergunta.';
COMMENT ON COLUMN pergunta.ativo IS 'Define se a pergunta está ativa (TRUE) ou inativa (FALSE).';

CREATE TABLE pergunta_setor (
	id_pergunta INT NOT NULL,
    id_setor INT NOT NULL,
	CONSTRAINT pk_pergunta_setor PRIMARY KEY (id_pergunta, id_setor),
    CONSTRAINT fk_pergunta_setor_pergunta
        FOREIGN KEY (id_pergunta)
        REFERENCES pergunta (id_pergunta)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT fk_pergunta_setor_setor
        FOREIGN KEY (id_setor)
        REFERENCES setor (id_setor)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
)

COMMENT ON TABLE pergunta_setor IS 'Tabela que armazena a relação entre pergunta e setor';
COMMENT ON COLUMN pergunta_setor.id_pergunta IS 'Identificador da pergunta (FK).';
COMMENT ON COLUMN pergunta_setor.id_setor IS 'Identificador do setor (FK).';


CREATE TABLE avaliacao (
	id_avaliacao SERIAL PRIMARY KEY,
	id_dispositivo INT NOT NULL,
	CONSTRAINT fk_avaliacao_dispositivo
        FOREIGN KEY (id_dispositivo)
        REFERENCES dispositivo (id_dispositivo)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
	data_hora_avaliacao TIMESTAMP NOT NULL DEFAULT NOW()
);

COMMENT ON TABLE avaliacao IS 'Tabela que armazena as avaliações do estabelecimento.';
COMMENT ON COLUMN avaliacao.id_avaliacao IS 'Identificador único da avaliação.';
COMMENT ON COLUMN avaliacao.id_dispositivo IS 'Identificador do dispositivo de onde foi feita a avaliação.';
COMMENT ON COLUMN avaliacao.data_hora_avaliacao IS 'Data/Hora de quando foi feita a avaliação';

CREATE TABLE resposta_avaliacao (
    id_avaliacao INT NOT NULL,
    id_pergunta INT NOT NULL,
    resposta SMALLINT NOT NULL CHECK (resposta BETWEEN 1 AND 10),
    feedback_textual TEXT NULL,
    CONSTRAINT pk_resposta_avaliacao PRIMARY KEY (id_avaliacao, id_pergunta),
    CONSTRAINT fk_resposta_avaliacao_avaliacao
        FOREIGN KEY (id_avaliacao)
        REFERENCES avaliacao (id_avaliacao)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT fk_resposta_avaliacao_pergunta
        FOREIGN KEY (id_pergunta)
        REFERENCES pergunta (id_pergunta)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

COMMENT ON TABLE resposta_avaliacao IS 'Tabela que armazena as respostas de cada pergunta em uma avaliação.';
COMMENT ON COLUMN resposta_avaliacao.id_avaliacao IS 'Identificador da avaliação (FK).';
COMMENT ON COLUMN resposta_avaliacao.id_pergunta IS 'Identificador da pergunta (FK).';
COMMENT ON COLUMN resposta_avaliacao.resposta IS 'Nota atribuída à pergunta (1 a 10).';
COMMENT ON COLUMN resposta_avaliacao.feedback_textual IS 'Comentário opcional do avaliador.';


