-- Sequencia de criação das tabelas de encaminhamentos e seus codigos SQL

CREATE TABLE
    servidores_encaminhados (
        id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
        nome VARCHAR(255),
        cpf VARCHAR(100),
        formacao VARCHAR(255),
        nte INT,
        municipio VARCHAR(100)
    );

CREATE TABLE
    provimentos_encaminhados (
        id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
        servidor_encaminhado_id INT,
        uee_id INT,
        data_encaminhamento DATE,
        data_assunsao DATE,
        obs TEXT,
        user_id INT,
        updated_at TIMESTAMP,
        created_at TIMESTAMP
    );

ALTER TABLE provimentos_encaminhados
ADD CONSTRAINT fk_servidor_encaminhado_id
FOREIGN KEY (servidor_encaminhado_id)
REFERENCES servidores_encaminhados(id);

ALTER TABLE provimentos_encaminhados
ADD CONSTRAINT fk_ueees_id
FOREIGN KEY (uee_id)
REFERENCES uees(id);

ALTER TABLE provimentos_encaminhados
MODIFY COLUMN user_id BIGINT UNSIGNED;

ALTER TABLE provimentos_encaminhados
ADD CONSTRAINT fk_useer_id
FOREIGN KEY (user_id)
REFERENCES users(id);

ALTER TABLE provimentos_encaminhados
ADD servidor_substituido_id INT;

ALTER TABLE provimentos_encaminhados
ADD CONSTRAINT fk_servidor_substituido_id
FOREIGN KEY (servidor_substituido_id)
REFERENCES servidores(id);

ALTER TABLE provimentos_encaminhados
ADD segundo_servidor_subistituido INT;

ALTER TABLE provimentos_encaminhados
ADD CONSTRAINT fk_segundo_servidor_substituido_id
FOREIGN KEY (segundo_servidor_subistituido)
REFERENCES servidores(id);