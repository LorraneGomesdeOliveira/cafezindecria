# Cafezin de Cria

Aplicacao PHP para exibir um cardapio e administrar produtos.

## Requisitos

- PHP com a extensao PDO MySQL
- MySQL com o banco configurado e a tabela `produtos` (colunas `id`, `tipo`, `nome`, `descricao`, `preco` e `imagem`)

## Configuracao

Configure estas variaveis de ambiente antes de iniciar o servidor:

- `DB_HOST` (opcional; padrao `localhost`)
- `DB_NAME` (opcional; padrao `cafézin_de_cria`)
- `DB_USER` e `DB_PASSWORD` (obrigatorias)
- `ADMIN_EMAIL` e `ADMIN_PASSWORD` (opcionais; devem ser definidas juntas para criar a conta administrativa inicial)

No PowerShell, por exemplo:

```powershell
$env:DB_HOST = "localhost"
$env:DB_NAME = "cafézin_de_cria"
$env:DB_USER = "seu_usuario"
$env:DB_PASSWORD = "sua_senha"
$env:ADMIN_EMAIL = "admin@exemplo.com"
$env:ADMIN_PASSWORD = "defina-uma-senha-forte"
php -S localhost:8000
```

Abra `http://localhost:8000`. Configure credenciais seguras no ambiente de hospedagem; nao as grave no codigo nem no repositorio.
