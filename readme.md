# Portal Esperança

Portal Escolar simples desenvolvido em PHP com autenticação, gerenciamento de notícias, enquetes e área de aluno.

---

# 📌 Visão Geral

Este projeto simula um portal escolar para alunos e administradores. Ele inclui:

- Login e controle de acesso por perfil
- Área administrativa para criação de notícias e enquetes
- Área do aluno com perfil e acesso a conteúdos institucionais
- Banco de dados MySQL/MariaDB
- Testes automatizados com PHPUnit e Playwright

---

# ⚙️ Tecnologias

- PHP
- MySQL / MariaDB
- HTML5
- Tailwind CSS
- JavaScript
- Playwright
- PHPUnit
- GitHub Actions

---

# 📂 Estrutura do Projeto

```bash
portal-simple/
├── .github/
│   └── workflows/ci.yml
├── app/
│   ├── includes/
│   │   ├── conexao.php
│   │   └── funcoes.php
│   └── processa/
│       ├── processa_cadastro.php
│       ├── processa_login.php
│       ├── processa_logout.php
│       └── processa_voto.php
├── assets/
│   ├── img/
│   └── perfil/
├── db/
│   └── projeto.sql
├── docs/
│   └── diagramas-uml-projetofinal.mdj
├── tests/
│   ├── *.php
│   └── *.spec.js
├── area_admin.php
├── area_aluno.php
├── biblioteca.php
├── cadastro.php
├── index.php
├── login.php
├── noticias.php
├── painel_admin.php
├── perfil_aluno.php
├── package.json
├── composer.json
├── phpunit.xml
├── run-tests.ps1
└── readme.md
```

---

# 🚀 Como Executar Localmente

## Requisitos

- XAMPP ou ambiente PHP + Apache
- MySQL/MariaDB
- Node.js + npm (para testes Playwright)
- Composer (para PHPUnit)

## Passos

1. Copie a pasta `portal-simple` para `C:\xampp\htdocs\`.
2. Inicie o Apache e o MySQL no XAMPP.
3. Crie e importe o banco de dados:
   - Abra phpMyAdmin (`http://localhost/phpmyadmin`)
   - Crie um novo banco chamado `projeto`
   - Importe o arquivo `db/projeto.sql`
4. Ajuste os dados de conexão em `app/includes/conexao.php` se necessário.
5. Acesse no navegador:

```text
http://localhost/portal-simple/
```

---

# 🗄️ Banco de Dados

O arquivo `db/projeto.sql` contém o dump completo do banco com todas as tabelas e estrutura.

Principais tabelas:

- `usuarios` (alunos e administradores)
- `noticias` (notícias institucionais)
- `enquetes` (enquetes/votações)
- `opcoes_enquete` (opções das enquetes)
- `votos` (votos dos alunos)

Para importar via MySQL CLI:

```bash
mysql -u root -p projeto < db/projeto.sql
```

---

# 🔐 Funcionalidades

## Autenticação

- login para administrador
- login para aluno
- controle de sessão
- páginas protegidas
- logout

## Área do Aluno

- perfil do estudante
- exibição de notícias e conteúdos institucionais

## Área do Administrador

- gerenciamento de notícias
- criação e exclusão de enquetes
- visão de alunos cadastrados

---

# 📊 Diagramas UML

Os diagramas da arquitetura e fluxos do projeto estão em `docs/diagramas-uml-projetofinal.mdj` (formato StarUML).

Para visualizar:

- Abra em [StarUML](https://staruml.io/)
- Inclui diagramas de sequência, atividades e estrutura do sistema

---

# 🧪 Testes

## PHPUnit

Use o script PowerShell:

```powershell
.
un-tests.ps1
```

Ou execute diretamente:

```bash
composer install
vendor/bin/phpunit -c phpunit.xml
```

## Playwright

Instale as dependências e execute:

```bash
npm install
npx playwright install
npx playwright test
```

---

# 💻 CI / GitHub Actions

O repositório inclui um fluxo de CI em `.github/workflows/ci.yml` que executa:

- testes PHPUnit
- testes Playwright

---

# 📌 Notas

- Arquivos de processamento de formulário ficam em `app/processa/`.
- Configurações de conexão de banco estão em `app/includes/conexao.php`.
- Recursos de imagem estão em `assets/img/` e `assets/perfil/`.

```bash
vendor/bin/phpunit
```

---

# ☁️ Deploy

O projeto pode ser hospedado em servidores compatíveis com PHP e MariaDB/MySQL.

## Hospedagem utilizada

- InfinityFree

---

# 🔒 Segurança e Qualidade

- Autenticação via sessões PHP
- Controle de acesso
- Validação de formulários
- Proteção de rotas administrativas
- Testes automatizados
- Integração contínua
- Validação de emails institucionais

---

# 📌 Resultados Esperados

- Centralização das informações escolares
- Melhor gerenciamento acadêmico
- Facilidade de acesso para alunos e administradores
- Melhor organização institucional
- Maior segurança no acesso ao sistema

---

# 🔮 Próximas Evoluções

- API REST
- Recuperação de senha
- Dashboard avançado
- Upload de arquivos
- Melhorias de segurança
- Notificações em tempo real

---

# 👨‍💻 Autor

Projeto desenvolvido para fins acadêmicos e de aprendizado em desenvolvimento web, testes automatizados e integração contínua.
