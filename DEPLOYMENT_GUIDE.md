# Guia de Implantação JCA Soluções Contábeis (TiDB + Vercel)

Este guia contém as instruções finais para conectar seu banco de dados TiDB Cloud e publicar o projeto na Vercel.

## 1. Configurando o Banco de Dados (TiDB Cloud)

1. No painel do TiDB Cloud, pegue seus dados de conexão.
2. No arquivo `.env` na raiz do projeto, preencha as seguintes variáveis:

```env
# Conexão para o Next.js (Prisma)
DATABASE_URL="mysql://USUARIO:SENHA@HOST:PORTA/jca_erp?sslaccept=strict"

# Conexão para o PHP Legado
DB_HOST="HOST"
DB_NAME="jca_erp"
DB_USER="USUARIO"
DB_PASS="SENHA"
DB_SSL="true"
```

## 2. Migrando o Schema para o TiDB

Após configurar o `.env`, execute o seguinte comando no terminal para criar as tabelas no TiDB automaticamente:

```bash
npx prisma db push
```

## 3. Implantação na Vercel

1. Garanta que salvou todas as alterações e fez o push para o seu repositório GitHub.
2. Na Vercel, importe o projeto.
3. **IMPORTANTE**: No passo de "Environment Variables", adicione todas as variáveis do seu arquivo `.env` (DATABASE_URL, DB_HOST, DB_NAME, DB_USER, DB_PASS, DB_SSL).
4. Clique em **Deploy**.

## Observações sobre a Estrutura Híbrida

- **Home Moderno**: Acessível em `domain.com/` (Next.js).
- **ERP Legado**: Acessível em `domain.com/index.php`.
- **Uploads**: Desativados "por enquanto" para evitar erros de escrita no servidor temporário da Vercel.

---
*Gerado por Antigravity*
