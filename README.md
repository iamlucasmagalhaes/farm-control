## Sobre
Um sistema completo de gestão agropecuária, permitindo administrar fazendas, animais e veterinários, além de gerar relatórios automatizados de produção, consumo e abate. O projeto utiliza Symfony 7, Twig e Doctrine, e é totalmente containerizado com Docker para facilitar instalação e execução.

![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white)
![Symfony](https://img.shields.io/badge/Symfony-7.0-000000?logo=symfony&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Containerized-2496ED?logo=docker&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green)
![Status](https://img.shields.io/badge/Status-Em%20Desenvolvimento-yellow)


## Como rodar o projeto

### 1. Suba os containers Docker
    docker compose up --build -d

### 2. Execute a instalação completa
    make install

O comando acima executa automaticamente:

* Composer install
* Criação do banco de dados
* Migrations
* Seed com dados de teste

Agora basta acessar o projeto em: http://localhost:8080

## Funcionalidades principais

* CRUD completo para:
    * Fazendas
    * Animais
    * Veterinários
* Paginação com KnpPaginator
* Interface responsiva com Bootstrap
* Flash messages
* Relatórios por fazenda:
    * Animais abatidos
    * Produção total de leite por semana
    * Consumo total de ração por semana
    * Animais até 1 ano que consomem mais de 500kg
