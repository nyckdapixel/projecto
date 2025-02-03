-- Criação do Banco de Dados
CREATE DATABASE gestao_vendas;
USE gestao_vendas;

-- Tabela de Usuários (Login)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);

-- Adicionar usuário padrão (Fátima)
INSERT INTO usuarios (usuario, senha) VALUES ('fatima', 'fatima123');
INSERT INTO usuarios (usuario, senha) VALUES ('user', 'user123');

-- Tabela de Clientes
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Produtos (Vinculada a um Cliente)
CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    nome VARCHAR(255) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    quantidade INT NOT NULL,
    descricao TEXT,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
);

-- Tabela Financeiro (Vinculada a um Cliente) - Entradas Financeiras
CREATE TABLE financeiro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    descricao VARCHAR(255) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    data_movimento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
);

-- Tabela Saídas Financeiras (Gastos) - Saídas Financeiras
CREATE TABLE saidas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    descricao VARCHAR(255) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    data_movimento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
);
