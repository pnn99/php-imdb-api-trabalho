# 🎬 NetFilmes - Catálogo de Filmes (TMDb)

> Projeto desenvolvido para a disciplina de Introdução a Software Livre do curso de Sistemas de Informação (IFMG – Campus Sabará).

## 📄 Resumo do Projeto
O **NetFilmes** é uma aplicação web que consome a API do TMDb (The Movie Database) para listar filmes populares, permitir pesquisas e exibir detalhes como sinopse, nota e data de lançamento. A interface é inspirada em plataformas de streaming, com foco em responsividade e usabilidade.

## 🎯 Objetivos
* Aplicar conceitos de desenvolvimento web (HTML, CSS, JS, PHP).
* Demonstrar o consumo de APIs RESTful.
* Praticar a gestão de projetos Open Source e fluxo de contribuição (Git/GitHub).

## ✨ Funcionalidades Atuais
* [x] Listagem de filmes populares em carrossel (Scroll Snap).
* [x] Pesquisa de filmes em tempo real.
* [x] Modal com detalhes do filme (Poster, Sinopse, Nota).
* [x] Sistema de Favoritos (Persistência via LocalStorage).
* [x] Proxy em PHP para proteção da API Key.

## 🚀 Como Executar o Projeto

### Pré-requisitos
* PHP 7.4 ou superior.
* Servidor web local (PHP built-in server, XAMPP ou Docker).

### Passo a Passo
1.  **Clone o repositório:**
    ```bash
    git clone [https://github.com/seu-usuario/php-imdb-api-trabalho.git](https://github.com/seu-usuario/php-imdb-api-trabalho.git)
    cd php-imdb-api-trabalho
    ```

2.  **Configuração da API:**
    * O projeto utiliza um arquivo `api.php` para comunicar com o TMDb.
    * Certifique-se de que a chave da API está configurada corretamente no backend.

3.  **Rodando o servidor:**
    ```bash
    php -S localhost:8000
    ```

4.  **Acesse:**
    Abra `http://localhost:8000` no seu navegador.

## 🤝 Como Contribuir
Contribuições são sempre bem-vindas! Veja o arquivo `CONTRIBUTING.md` para saber como começar.

## 📜 Licença
Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.