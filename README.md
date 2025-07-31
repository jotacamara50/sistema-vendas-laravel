Sistema de Vendas em Laravel 🚀
Um projeto feito em Laravel 11 para gerenciar vendas, clientes e produtos de forma simples e direta.

O que ele faz? ✨
🔐 Autenticação: Sistema de login completo para os vendedores.

📦 Produtos: Você pode adicionar, editar, listar e excluir produtos do seu catálogo.

👥 Clientes: Gerenciamento completo da sua base de clientes.

💰 Vendas:

Criação de vendas com múltiplos itens.

Gerador de parcelas com datas e valores customizáveis.

Edição e exclusão de vendas já registradas.

🔍 Busca Avançada: Filtros por data e cliente na lista de vendas.

📄 PDF: Download de um resumo completo da venda com um clique.

Tech Stack 💻
Backend: Laravel 11 & PHP 8.2+

Frontend: Blade, Tailwind CSS & JavaScript (jQuery)

Banco de Dados: SQL (MySQL/MariaDB)

PDFs: barryvdh/laravel-dompdf

Como Rodar o Projeto na sua Máquina 🛠️
Para colocar o sistema no ar, siga estes passos:

1. Preparando o Terreno:

Primeiro, clone o repositório e entre na pasta do projeto.

git clone https://github.com/jotacamara50/sistema-vendas-laravel.git
cd sistema-vendas-laravel

Depois, instale todas as dependências do PHP e do JavaScript.

composer install
npm install

2. Configurando o Ambiente:

Copie o .env.example para criar seu próprio arquivo de configuração.

cp .env.example .env

Agora, abra o arquivo .env e configure a conexão com seu banco de dados (DB_DATABASE, DB_USERNAME, DB_PASSWORD).

Depois, gere a chave da aplicação:

php artisan key:generate

3. Criando e Populando o Banco:

Execute as migrations para criar todas as tabelas.

php artisan migrate

⚠️ Passo Importante! Para o sistema funcionar, você precisa cadastrar as formas de pagamento. Rode o seeder:

php artisan db:seed --class=PaymentMethodSeeder

4. Subindo os Servidores:

Você precisa de dois terminais abertos.

No primeiro, suba o servidor do Laravel:

php artisan serve

No segundo, inicie o Vite para compilar o CSS e JS:

npm run dev

Pronto! Agora é só acessar http://127.0.0.1:8000 no seu navegador.