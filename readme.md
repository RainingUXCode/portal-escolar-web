# 🏫 Projeto Acadêmico: Colégio Esperança - Portal Estudantil

Este projeto é desenvolvido como parte das aulas de **Programação Web** e simula um portal web para uma instituição de ensino fictícia, permitindo a **autenticação** de alunos, administradores e participação em **enquetes** com resultados em tempo real.

O foco deste projeto é demonstrar a lógica básica de back-end, interação com banco de dados e controle de acesso via sessões.

---

## ✨ Funcionalidades Principais

- **Sistema de Autenticação:** Login e Cadastro de novos estudantes (`usuarios`).
- **Controle de Acesso:** Separação entre a área do aluno (`area_aluno.php`) e a área do administrador (`area_admin.php`) usando o campo `nivel_acesso` da tabela `usuarios`.
- **Gestão de Enquetes:**
  - **Voto Único:** Impede que o mesmo aluno vote mais de uma vez na mesma enquete (`processa_voto.php`).
  - **Resultados em Tempo Real:** Cálculo dinâmico da contagem e porcentagem dos votos usando consultas SQL de agregação (`COUNT` e `GROUP BY`).
- **Visualização de Alunos:** O administrador pode visualizar a lista completa de estudantes matriculados (`painel_admin.php`).

---

## 🛠️ Tecnologias Utilizadas

| Componente         | Tecnologia                     | Observações                                        |
| :----------------- | :----------------------------- | :------------------------------------------------- |
| **Backend**        | PHP 7+ (Procedural)            | Lógica de servidor e processamento de dados.       |
| **Banco de Dados** | MySQL                          | Armazenamento de usuários, enquetes e votos.       |
| **Driver de BD**   | MySQLi (Extensão Nativa)       | Conexão simples e direta com o banco de dados.     |
| **Frontend**       | HTML5 / Tailwind CSS (CDN)     | Estrutura da página e estilos visuais responsivos. |
| **Servidor**       | Apache HTTP Server (via XAMPP) | Ambiente local de execução.                        |

---

## ⚙️ Configuração e Instalação (Passo a Passo)

Para executar o projeto em seu ambiente local, siga as instruções abaixo:

### 1. Requisitos

- Servidor web com suporte a PHP e MySQL (Recomendado: **XAMPP**).

### 2. Configuração do Projeto

1.  **Copie ou Baixe:** Baixe todos os arquivos para a pasta de projetos do seu servidor (ex: `C:\xampp\htdocs\portal-escola`).
2.  **Criação do Banco:**
    - Acesse o phpMyAdmin (`http://localhost/phpmyadmin`).
    - Crie um novo banco de dados chamado **`projeto`**.
    - O script de criação do banco de dados está localizado em **database/projeto.sql**. Importe-o após criar o BD projeto, mas caso não consiga, faça o passo seguinte.
    - **Crie** as quatro tabelas essenciais: `usuarios`, `enquetes`, `opcoes_enquete`, e `votos`.
3.  **Configuração de Conexão:**
    - O arquivo de conexão `includes/conexao.php` está pré-configurado para o padrão do XAMPP (`user="root"`, `pass=""`). Se você usa senha no seu MySQL, ajuste este arquivo.

### 3. Acessos para Teste

Após a instalação, você pode acessar e testar as seguintes funcionalidades:

| Nível de Acesso | Acesso Inicial                                                                                                                      | Credenciais de Exemplo                                    |
| :-------------- | :---------------------------------------------------------------------------------------------------------------------------------- | :-------------------------------------------------------- |
| **Aluno**       | `http://localhost/portal-simple/cadastro.php`                                                                                       | Crie um novo aluno pelo formulário.                       |
| **Admin**       | **Necessário Inserção Manual:** Insira um registro na tabela `usuarios` com `email='admin@escola.com.br'` e `nivel_acesso='admin'`. | **E-mail:** `admin@escola.com.br` / **Senha:** `admin123` |
