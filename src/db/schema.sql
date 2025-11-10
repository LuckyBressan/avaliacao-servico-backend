DROP TABLE IF EXISTS resposta_avaliacao;
DROP TABLE IF EXISTS pergunta_setor;
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

CREATE TABLE pergunta (
    id_pergunta SERIAL PRIMARY KEY,
    texto TEXT NOT NULL,
    ativo BOOLEAN NOT NULL DEFAULT TRUE
);

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
);

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


-- ===============================================
-- 📋 POPULAÇÃO INICIAL DO BANCO DE DADOS
-- Sistema de Avaliação de Estabelecimento
-- ===============================================

-- 🔹 SETORES
INSERT INTO setor (nome, ativo) VALUES
('Atendimento', TRUE),
('Limpeza', TRUE),
('Alimentação', TRUE),
('Segurança', TRUE),
('Administração', TRUE);

-- 🔹 DISPOSITIVOS
INSERT INTO dispositivo (nome, ativo, id_setor) VALUES
('Totem - Entrada Principal', TRUE, 1),
('Totem - Corredor Central', TRUE, 2),
('Totem - Praça de Alimentação', TRUE, 3),
('Totem - Saída', TRUE, 4);

-- 🔹 PERGUNTAS
INSERT INTO pergunta (texto, ativo) VALUES
('Como você avalia a cordialidade e simpatia dos atendentes?', TRUE),
('O tempo de espera para ser atendido foi adequado?', TRUE),
('O ambiente estava limpo e organizado?', TRUE),
('Os banheiros estavam limpos e equipados?', TRUE),
('A qualidade e sabor da comida atenderam suas expectativas?', TRUE),
('O tempo de preparo e entrega dos alimentos foi satisfatório?', TRUE),
('Você se sentiu seguro durante sua permanência no local?', TRUE),
('Os seguranças foram atenciosos e prestativos?', TRUE),
('Você recomendaria este estabelecimento para outras pessoas?', TRUE);

-- 🔹 RELAÇÃO PERGUNTA_SETOR
-- (vincula perguntas aos setores correspondentes)
INSERT INTO pergunta_setor (id_pergunta, id_setor) VALUES
(1, 1),
(2, 1),
(3, 2),
(4, 2),
(5, 3),
(6, 3),
(7, 4),
(8, 4);

-- 🔹 AVALIAÇÕES
-- simulando avaliações realizadas em diferentes dispositivos
INSERT INTO avaliacao (id_dispositivo, data_hora_avaliacao) VALUES
(1, NOW() - INTERVAL '3 days'),
(3, NOW() - INTERVAL '2 days'),
(2, NOW() - INTERVAL '1 day'),
(4, NOW());

-- 🔹 RESPOSTAS DAS AVALIAÇÕES
-- Avaliação 1 (Atendimento)
INSERT INTO resposta_avaliacao (id_avaliacao, id_pergunta, resposta, feedback_textual) VALUES
(1, 1, 9, 'Funcionários muito educados e prestativos.'),
(1, 2, 8, 'Um pouco de fila, mas rápido.'),
(1, 9, 10, 'Excelente experiência!');

-- Avaliação 2 (Alimentação)
INSERT INTO resposta_avaliacao (id_avaliacao, id_pergunta, resposta, feedback_textual) VALUES
(2, 5, 10, 'Comida deliciosa e quente.'),
(2, 6, 7, 'Poderia ter sido servido um pouco mais rápido.'),
(2, 9, 9, 'Voltaria com certeza.');

-- Avaliação 3 (Limpeza)
INSERT INTO resposta_avaliacao (id_avaliacao, id_pergunta, resposta, feedback_textual) VALUES
(3, 3, 9, 'Ambiente bem limpo.'),
(3, 4, 6, 'Banheiro estava sem papel.'),
(3, 9, 8, 'De modo geral, tudo bom.');

-- Avaliação 4 (Segurança)
INSERT INTO resposta_avaliacao (id_avaliacao, id_pergunta, resposta, feedback_textual) VALUES
(4, 7, 10, 'Me senti seguro o tempo todo.'),
(4, 8, 9, 'Os seguranças foram gentis.'),
(4, 9, 10, 'Ambiente muito agradável e confiável.');
