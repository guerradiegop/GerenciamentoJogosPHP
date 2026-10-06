# GameVault - Gerenciamento de Jogos em PHP

Aplicação web CRUD desenvolvida em **PHP, HTML e CSS** para cadastrar e gerenciar uma coleção pessoal de jogos. Cada usuário possui sua própria biblioteca, com identificação de plataforma, formato (digital/online ou disco físico) e, para jogos digitais, a loja vinculada (Steam, EPIC, Battle.net, GOG e outras).

## Funcionalidades

- Landing page pública com apresentação do sistema e acessos para login/cadastro.
- Cadastro de usuário com nome, e-mail, CPF, telefone, gênero de jogos preferido e senha.
- Validação de CPF e unicidade de e-mail/CPF.
- Autenticação segura com `password_hash()` e `password_verify()`; nenhuma senha é armazenada em texto puro.
- Sessão autenticada com regeneração de ID no login e cookie `HttpOnly`/`SameSite=Lax`.
- Proteção CSRF nas operações POST.
- CRUD completo de jogos: cadastro, listagem/busca, edição e exclusão.
- Isolamento por usuário: consultas de jogos sempre filtradas por `user_id`.
- Dashboard com KPIs de total, digitais, físicos e plataformas, além de gráficos por plataforma, formato e loja.
- Banco MySQL/MariaDB acessado por PDO, com script SQL versionado em `database/schema.sql`.
- Layout responsivo em CSS puro; gráficos com Chart.js via CDN.

## Requisitos

- PHP 8.1 ou superior.
- Extensões PDO e PDO_MYSQL habilitadas.
- MySQL 8+ ou MariaDB equivalente.
- Navegador com acesso à internet para carregar o Chart.js da CDN (somente os gráficos dependem disso).

## Como executar

1. Clone este repositório:

```bash
git clone https://github.com/guerradiegop/GerenciamentoJogosPHP.git
cd GerenciamentoJogosPHP
```

2. Crie o banco e as tabelas importando o script SQL:

```bash
mysql -u root -p < database/schema.sql
```

3. Configure as credenciais de acesso. A aplicação lê `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` e `DB_PASS` do ambiente. Os valores padrão de desenvolvimento estão em `app/config.php` e um exemplo está em `.env.example`. Exemplo no Linux/macOS:

```bash
export DB_HOST=127.0.0.1
export DB_PORT=3306
export DB_NAME=gamevault
export DB_USER=root
export DB_PASS='sua_senha'
```

4. Inicie o servidor embutido do PHP usando `public` como document root:

```bash
php -S localhost:8000 -t public
```

5. Abra no navegador:

```text
http://localhost:8000
```

> Observação: o PHP não carrega arquivos `.env` automaticamente. O `.env.example` documenta as variáveis esperadas; defina-as no ambiente, no Apache/Nginx/PHP-FPM ou adapte o projeto para usar uma biblioteca de `.env`.

## Estrutura do projeto

```text
GerenciamentoJogosPHP/
├── app/
│   ├── auth.php          # login, logout, usuário atual e proteção de rotas
│   ├── config.php        # sessão e configurações gerais
│   ├── database.php      # conexão PDO com MySQL/MariaDB
│   ├── functions.php     # CSRF, CPF, escaping, flash e utilitários
│   └── layout.php        # cabeçalho/rodapé compartilhados
├── database/
│   └── schema.sql        # script SQL de criação do banco e tabelas
├── docs/
│   └── DOCUMENTACAO.pdf  # documentação funcional e técnica
└── public/
    ├── assets/css/style.css
    ├── index.php         # landing page
    ├── register.php      # cadastro de usuário
    ├── login.php         # autenticação
    ├── logout.php        # encerramento de sessão
    ├── dashboard.php     # KPIs e gráficos
    ├── games.php         # listagem e busca
    ├── game_form.php     # create/update
    └── game_delete.php   # delete
```

## Banco de dados

O projeto utiliza **MySQL/MariaDB via PDO**. O arquivo `database/schema.sql` cria o banco `gamevault`, as tabelas, índices, chaves únicas e relacionamento entre usuários e jogos. A aplicação não executa migrações automaticamente.


### `users`

Armazena dados cadastrais e `password_hash`. O campo `email` e o `cpf` são únicos.

### `games`

Armazena os jogos e possui `user_id` como chave estrangeira. `media_type` aceita `online` ou `physical`; `store` é preenchida para versões digitais.

## Segurança adotada

- Senhas: hash adaptativo com `PASSWORD_DEFAULT`.
- SQL Injection: comandos SQL com `PDO::prepare()` e parâmetros.
- XSS: saída de conteúdo dinâmico com `htmlspecialchars()` pela função `e()`.
- CSRF: token aleatório por sessão validado em formulários POST.
- Autorização: update/delete/select de jogos exigem simultaneamente `id` e `user_id`.
- Sessão: ID regenerado após login e logout destrói a sessão.

> Em produção, use HTTPS, configure cabeçalhos de segurança no servidor web, mantenha PHP atualizado e considere CSP para limitar recursos externos.

## Fluxo principal

1. Visitante acessa a landing page.
2. Cria uma conta em `/register.php`.
3. Entra em `/login.php`.
4. É redirecionado para `/dashboard.php`.
5. Cadastra e gerencia jogos em `/games.php` e `/game_form.php`.
6. A dashboard consolida automaticamente os dados da própria biblioteca.

## Testes rápidos

Valide a sintaxe PHP:

```bash
find app public -name '*.php' -print0 | xargs -0 -n1 php -l
```

## Licença

Projeto educacional. Você pode adaptar a licença conforme a finalidade do repositório.
