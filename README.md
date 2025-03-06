# Locaseguro

### Prova Técnica – Desenvolvimento de API em Laravel e Front-End Básico

Este repositório contém o código de um projeto de desafio técnico.

## Objetivo

O objetivo deste projeto é criar:
- Uma API em **Laravel** para realizar o cadastro de um imóvel e associá-lo a um proprietário.
- Um **frontend em React** que permita o cadastro do imóvel e a visualização das informações.

---

## Tecnologias Utilizadas

### Backend

- **PHP 8.2**
- **Docker** (para containers de desenvolvimento)
- **MySQL**
- **Laravel 11+** (framework PHP)
- **Arquitetura e Princípios**
  - **DDD (Domain Driven Design)**: Design baseado nos conceitos de modelagem de domínio.
  - **TDD (Test Driven Development)**: Desenvolvimento orientado a testes.
  - **Princípios SOLID**:
    - **Single Responsibility Principle (SRP)**: Cada classe deve ter um único motivo para mudar.
    - **Open/Closed Principle (OCP)**: Entidades devem ser abertas para extensão, mas fechadas para modificação.
    - **Liskov Substitution Principle (LSP)**: Subtipos devem ser substituíveis pelos seus tipos base.
    - **Interface Segregation Principle (ISP)**: Classes não devem ser forçadas a implementar interfaces que não utilizam.
    - **Dependency Inversion Principle (DIP)**: Dependa de abstrações, não de implementações.
  - **Clean Architecture**: Arquitetura limpa e desacoplada.
  - **Repository Pattern**: Padrão para abstração do acesso a dados.
  - **Ports and Adapters**: Conceito para desacoplamento entre as dependências externas e o core do sistema.
- **PSR Standards**:
  - **PSR-1**: Basic Coding Standard
  - **PSR-3**: Logger Interface
  - **PSR-4**: Autoloader
  - **PSR-12**: Extended Coding Style Guide

### Frontend

- **React** (biblioteca JavaScript para a interface de usuário)
- **TypeScript** (superset do JavaScript para maior tipagem estática e segurança)

---

## Requisitos para Rodar o Projeto

1. **Docker**: Para facilitar o gerenciamento de dependências e garantir que o ambiente de desenvolvimento seja consistente.
2. **Node.js v22.14.0+**: Para o frontend.

---

### Como Rodar o Projeto
---

### 1. Subir os Containers do Backend

Crie um arquivo .env a partir do exemplo .env.example:

```bash
cp .env.example .env
```

Depois na raiz do projeto, execute o seguinte comando para subir os containers Docker que contêm a API e o banco de dados MySQL:

```bash
docker-compose up -d
```

Isso iniciará os containers em segundo plano e sua API estará rodando.

### 2. Rodar o frontend

Primeiro, entre na pasta do frontend e instale as dependências necessárias:

```bash
cd frontend && npm install
```

Depois, crie um arquivo .env.local a partir do exemplo .env.example:

```bash
cp .env.example .env.local
```

Agora, inicie o servidor de desenvolvimento:

```
npm run dev
```

Isso rodará o frontend em modo de desenvolvimento, e o site estará acessível no navegador.

### 3. Acessando a Aplicação

- **Frontend**: Acesse o frontend através de `http://localhost:5173` (ou o endereço fornecido após rodar o `npm run dev`).
- **API**: A API estará disponível em `http://localhost:8001` (ou o endereço configurado no `docker-compose.yml`).

---

## Estrutura do Projeto

### Backend

- **backend/app/**: Contém a aplicação Laravel, incluindo controllers, models, serviços, etc.
- **backend/tests/**: Contém os testes da aplicação, organizados para garantir a integridade do sistema.
- **docker-compose.yml**: Arquivo de configuração para a criação dos containers Docker.

### Frontend

- **frontend/src/**: Código-fonte do React, com componentes para cadastro de imóveis e visualização de dados.
- **frontend/.env.local**: Configuração local do ambiente para a aplicação frontend.

---

#### Para rodar os testes automatizados rode dentro do container da api
```bash
composer run test:integration
```