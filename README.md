# SCP - Sistema de Carência e Provimento - Gestão Educacional Inteligente

![Status](https://img.shields.io/badge/status-em_desenvolvimento-yellow)
![Laravel](https://img.shields.io/badge/Laravel-9.x-red)
![PHP](https://img.shields.io/badge/PHP-8.0+-blue)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange)

## 📖 Sobre o Projeto

O **Sistema de Carência e Provimento (SCP)** é uma aplicação web desenvolvida para centralizar e automatizar o gerenciamento de processos relacionados à carência e provimento no contexto de gestão educacional e administrativa. O sistema resolve a necessidade de controle eficiente de vagas em unidades escolares, gerenciamento de servidores, processos de provimento, regularização funcional e geração de relatórios detalhados para órgãos educacionais.

O SCP é destinado a **gestores educacionais, técnicos da CPG (Coordenação de Provimento e Gestão), administradores de unidades escolares e equipes de recursos humanos** que necessitam de uma ferramenta robusta para o controle e acompanhamento de processos de carência e provimento de servidores no sistema educacional.

## 🛠️ Tecnologias Utilizadas

### **Backend**
- **Laravel 9.x** - Framework PHP robusto para desenvolvimento web
- **PHP 8.0+** - Linguagem de programação moderna
- **Laravel Sanctum** - Autenticação e autorização
- **Laravel Breeze** - Sistema de autenticação
- **Laravel Tinker** - Console interativo

### **Frontend**
- **Blade Templates** - Engine de templates do Laravel
- **Tailwind CSS 3.x** - Framework CSS utilitário
- **Alpine.js 3.x** - Framework JavaScript minimalista
- **Vite 4.x** - Build tool moderno
- **Axios** - Cliente HTTP para requisições AJAX

### **Banco de Dados**
- **MySQL 8.0+** - Sistema de gerenciamento de banco de dados relacional
- **Eloquent ORM** - Mapeamento objeto-relacional do Laravel

### **Ferramentas e Bibliotecas**
- **Maatwebsite/Excel** - Geração e importação de planilhas Excel
- **Pest** - Framework de testes PHP
- **Laravel Pint** - Code style fixer
- **PostCSS** - Processador CSS
- **Autoprefixer** - Adiciona prefixos CSS automaticamente

### **Testes**
- **Pest PHP** - Framework de testes moderno
- **Mockery** - Biblioteca de mocking
- **Laravel Testing** - Utilitários de teste do Laravel

## ✅ Pré-requisitos

Antes de começar, certifique-se de ter instalado em sua máquina:

- **PHP 8.0.2 ou superior**
- **Composer** - Gerenciador de dependências PHP
- **Node.js 16.x ou superior** - Para ferramentas de frontend
- **NPM ou Yarn** - Gerenciador de pacotes JavaScript
- **MySQL 8.0 ou superior** - Banco de dados
- **Git** - Controle de versão

### **Extensões PHP Necessárias**
- BCMath PHP Extension
- Ctype PHP Extension
- cURL PHP Extension
- DOM PHP Extension
- Fileinfo PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PCRE PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

## 🚀 Instalação e Configuração

### **1. Clone o Repositório**
```bash
git clone https://github.com/seu-usuario/SCP.git
cd SCP
```

### **2. Instale as Dependências PHP**
```bash
composer install
```

### **3. Instale as Dependências JavaScript**
```bash
npm install
```

### **4. Configure as Variáveis de Ambiente**
```bash
cp .env.example .env
```

Edite o arquivo `.env` com suas configurações:
```env
APP_NAME="SCP - Sistema de Carência e Provimento"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=scp_database
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### **5. Gere a Chave da Aplicação**
```bash
php artisan key:generate
```

### **6. Configure o Banco de Dados**
```bash
# Execute as migrations
php artisan migrate

# Execute os seeders (se disponíveis)
php artisan db:seed
```

### **7. Configure o Storage**
```bash
php artisan storage:link
```

### **8. Compile os Assets**
```bash
# Para desenvolvimento
npm run dev

# Para produção
npm run build
```

## ▶️ Executando o Projeto

### **Modo de Desenvolvimento**
```bash
# Terminal 1: Servidor Laravel
php artisan serve

# Terminal 2: Vite para assets (se necessário)
npm run dev
```

A aplicação estará disponível em: `http://localhost:8000`

### **Modo de Produção**
```bash
# Otimize a aplicação
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Compile os assets para produção
npm run build

# Configure seu servidor web (Apache/Nginx) para apontar para a pasta public/
```

### **Executando Testes**
```bash
# Execute todos os testes
php artisan test

# Execute testes específicos
php artisan test --filter=NomeDoTeste

# Com cobertura de código
php artisan test --coverage
```

### **Comandos Úteis**
```bash
# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Recriar banco de dados
php artisan migrate:fresh --seed

# Verificar rotas
php artisan route:list
```

## 🏗️ Arquitetura do Sistema

### **Módulos Principais**
- **Gestão de Carências** - Registro e controle de vagas em unidades escolares
- **Gestão de Provimentos** - Processo de preenchimento de vagas por servidores
- **Reserva de Vagas** - Sistema de reserva e bloqueio de vagas
- **Gestão de Servidores** - Cadastro e controle de servidores
- **Unidades Escolares** - Gestão de escolas e unidades educacionais
- **Relatórios** - Geração de relatórios e exportação para Excel
- **Regularização Funcional** - Processos de regularização
- **Aposentadorias** - Gestão de processos de aposentadoria
- **Sistema de Logs** - Auditoria e rastreamento de ações

### **Estrutura de Diretórios**
```
SCP/
├── app/
│   ├── Http/Controllers/     # Controladores da aplicação
│   ├── Models/              # Modelos Eloquent
│   ├── Mail/               # Classes de email
│   └── Providers/          # Service Providers
├── database/
│   ├── migrations/         # Migrations do banco
│   └── seeders/           # Seeders para dados iniciais
├── resources/
│   ├── views/             # Templates Blade
│   ├── css/              # Estilos CSS
│   └── js/               # JavaScript
├── public/               # Arquivos públicos
├── routes/              # Definição de rotas
└── tests/              # Testes automatizados
```

## 🔐 Sistema de Autenticação e Permissões

O sistema utiliza **Laravel Sanctum** para autenticação e implementa um sistema de perfis:
- **Administrador** - Acesso total ao sistema
- **CPG Técnico** - Acesso a funcionalidades técnicas
- **Usuário Padrão** - Acesso limitado conforme permissões

## 📊 Funcionalidades Principais

### **Gestão de Carências**
- Cadastro de carências reais e temporárias
- Controle por turnos (matutino, vespertino, noturno)
- Vinculação com disciplinas e áreas
- Sistema de homologação

### **Gestão de Provimentos**
- Busca e seleção de servidores
- Processo de encaminhamento
- Validação de documentos
- Controle de status e tramitação

### **Reserva de Vagas**
- Sistema de reserva por blocos
- Justificativa de reservas
- Geração de termos de reserva
- Controle de prazos

### **Relatórios e Exportação**
- Relatórios dinâmicos
- Exportação para Excel
- Filtros avançados
- Dashboards de acompanhamento

## 🤝 Como Contribuir

1. **Fork o Projeto**
   ```bash
   # Clone seu fork
   git clone https://github.com/seu-usuario/SCP.git
   ```

2. **Crie uma Branch para sua Feature**
   ```bash
   git checkout -b feature/nova-funcionalidade
   ```

3. **Faça Commit das Alterações**
   ```bash
   git commit -m "Adiciona nova funcionalidade"
   ```

4. **Push para a Branch**
   ```bash
   git push origin feature/nova-funcionalidade
   ```

5. **Abra um Pull Request**

### **Padrões de Código**
- Siga o **PSR-12** para PHP
- Use **Laravel Pint** para formatação automática
- Escreva testes para novas funcionalidades
- Documente mudanças significativas


## 👥 Desenvolvido por

**Marcio Paranhos** - [GitHub](https://github.com/MarcioParanhos)

## 📞 Suporte

Para suporte e dúvidas sobre o sistema:
- Abra uma **Issue** no repositório
- Entre em contato com a equipe de desenvolvimento

---

> **Nota:** Este projeto está em constante evolução. Novas funcionalidades são implementadas regularmente para atender às necessidades do setor educacional.

**Desenvolvido com ❤️ para a educação brasileira.**