# SCP - Sistema de Carência e Provimento

O **SCP** é uma aplicação desenvolvida para facilitar o gerenciamento de processos relacionados à carência e provimento no contexto de gestão educacional e administrativa. 

## Funcionalidades Principais

- **Gestão de Carências**: Registro e acompanhamento de carências no sistema.
- **Gestão de Provimentos**: Controle e registro de provimentos realizados.
- **Relatórios Dinâmicos**: Emissão de relatórios detalhados e customizáveis.
- **Controle de Logs**: Registro de atividades do sistema para maior transparência e auditoria.
- **Gestão de Usuários e Permissões**: Controle de acessos de acordo com os perfis dos usuários.

## Tecnologias Utilizadas

- **Backend**: Laravel
- **Frontend**: Blade templates
- **Banco de Dados**: MySQL
- **Outras Dependências**:
  - Axios
  - SweetAlert2
  - Moment.js

## Organização do Projeto

### Estrutura de Diretórios

- **app/**: Contém os controladores, modelos e lógica de negócios.
- **resources/views/**: Contém os arquivos Blade para renderização das páginas.
- **public/**: Arquivos públicos como CSS, JS e imagens.
- **routes/**: Arquivos de rotas para web e API.
- **database/**: Migrations e seeders para o banco de dados.

### Fluxo Principal

1. O usuário acessa o sistema via interface web.
2. As requisições são enviadas para os controladores definidos no Laravel.
3. As respostas são processadas e renderizadas utilizando templates Blade.
4. Logs são registrados automaticamente para auditoria.

---

> **Nota:** Este projeto está em constante evolução e novas funcionalidades estão sendo implementadas para atender às necessidades do setor.

---

## 📜 Licença

Este projeto é **proprietário** e não está licenciado para distribuição pública.

---

Desenvolvido com ❤️ por [Marcio Paranhos](https://github.com/MarcioParanhos)