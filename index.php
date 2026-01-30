<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NETFILMES - TMDb</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --netflix-red: #e50914;
            --dark-gray: #1a1a1a;
        }

        body {
            background-color: #000;
            color: white;
            font-family: 'Arial', sans-serif;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .banner {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.8)), url('fundo_banner_inicial.png');
            background-position: center;
            background-size: cover;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* --- MELHORIA: CSS Scroll Snap --- */
        .carousel-track-container {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            gap: 15px;
            padding-bottom: 20px;
            width: 95%;
            margin: 0 auto;
            scroll-behavior: smooth;
        }
        
        .carousel-track-container::-webkit-scrollbar {
            height: 8px;
        }
        .carousel-track-container::-webkit-scrollbar-track {
            background: #1a1a1a;
        }
        .carousel-track-container::-webkit-scrollbar-thumb {
            background: var(--netflix-red);
            border-radius: 4px;
        }

        .carousel-track {
            display: flex;
        }

        .card-movie {
            scroll-snap-align: start;
            flex: 0 0 auto;
            min-width: 18rem;
            background-color: var(--dark-gray);
            border: 1px solid #333;
            color: white;
            transition: transform 0.3s;
        }

        .card-movie:hover {
            transform: translateY(-5px);
            border-color: var(--netflix-red);
            z-index: 10;
        }

        .card-movie img {
            height: 380px;
            object-fit: cover;
        }

        .carousel-controls {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 10px;
        }

        .btn-nav {
            background: var(--netflix-red);
            border: none;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 1.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }
        
        .btn-nav:hover {
            background: #b20710;
        }

        .search-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .search-input {
            background: #222;
            border: 1px solid #444;
            color: white;
        }

        .search-input:focus {
            background: #333;
            color: white;
            border-color: var(--netflix-red);
            box-shadow: none;
        }

        /* Estilo Favoritos */
        .favorites-section {
            padding: 20px 5%;
        }

        .fav-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .card-fav {
            background: #111;
            border: 1px solid #222;
        }

        .card-fav img {
            height: 250px;
            object-fit: cover;
        }

        /* --- MELHORIA: Footer --- */
        footer {
            margin-top: auto; /* Empurra o footer para o final da página */
            background-color: #000;
        }
    </style>
</head>

<body>

    <div class="banner">
        <h1 class="display-1 fw-bold text-danger">NETFILMES</h1>
    </div>

    <div class="search-container">
        <h3 class="text-center mb-3">Pesquise seu filme favorito</h3>
        <div class="input-group mb-4">
            <input type="text" id="searchInput" class="form-control search-input" placeholder="Digite o nome...">
            <button class="btn btn-danger" onclick="searchMovie()">Pesquisar</button>
        </div>

        <div class="d-flex justify-content-center gap-2 flex-wrap">
            <button class="btn btn-outline-light btn-sm rounded-pill px-3" onclick="fetchMovies()">
                🔥 Populares
            </button>
            <button class="btn btn-outline-warning btn-sm rounded-pill px-3" onclick="fetchCategory('movie/top_rated', '⭐ Melhores Avaliados')">
                ⭐ Melhores Avaliados
            </button>
            <button class="btn btn-outline-info btn-sm rounded-pill px-3" onclick="fetchCategory('movie/upcoming', '📅 Lançamentos em Breve')">
                📅 Em Breve
            </button>
        </div>
    </div>

    <hr class="border-secondary mx-5">

    <div class="carousel-section">
        <h2 class="px-5 mb-4 text-danger" id="sectionTitle">Destaques</h2>
        
        <div id="loadingSpinner" class="text-center my-5 d-none">
            <div class="spinner-border text-danger" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Carregando...</span>
            </div>
            <p class="text-secondary mt-2">Buscando filmes...</p>
        </div>

        <div class="carousel-track-container" id="carouselContainer">
            <div class="carousel-track" id="carouselTrack"></div>
        </div>
        
        <div class="carousel-controls">
            <button class="btn-nav" id="prevBtn">❮</button>
            <button class="btn-nav" id="nextBtn">❯</button>
        </div>
    </div>

    <section class="favorites-section" id="favSection" style="display: none;">
        <h2 class="mb-4 text-warning">Meus Favoritos ★</h2>
        <div class="fav-grid" id="favoritesContainer"></div>
    </section>

    <footer class="py-4 border-top border-secondary text-center text-secondary">
        <div class="container">
            <p class="mb-1 text-light">Desenvolvido por <strong>Paulo Nogueira do Nascimento</strong></p>
            <p class="small mb-3">Sistemas de Informação - IFMG Sabará</p>
            
            <div class="d-flex justify-content-center mb-3">
                <a href="https://github.com/seu-usuario/php-imdb-api-trabalho" target="_blank" class="btn btn-outline-light btn-sm d-flex align-items-center gap-2">
                    <svg height="20" width="20" viewBox="0 0 16 16" fill="white"><path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"></path></svg>
                    Ver código no GitHub
                </a>
            </div>
            
            <p class="mb-0" style="font-size: 0.75rem; opacity: 0.5;">
                Dados fornecidos por <a href="https://www.themoviedb.org/" target="_blank" class="text-secondary">TMDb</a>. 
                Este projeto acadêmico usa a API do TMDb mas não é certificado por eles.
            </p>
        </div>
    </footer>

    <div class="modal fade" id="movieModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-dark text-white border-secondary">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img id="modalPoster" src="" class="img-fluid rounded shadow">
                        </div>
                        <div class="col-md-8">
                            <div class="d-flex justify-content-between align-items-start">
                                <h6><strong>Sinopse</strong></h6>
                                <button id="btnFav" class="btn btn-outline-warning btn-sm">Favoritar ★</button>
                            </div>
                            <p id="modalOverview" class="text-secondary mt-2"></p>
                            <div class="mt-4">
                                <span class="badge bg-warning text-dark me-2">Nota: <span id="modalRating"></span></span>
                                <span>Lançamento: <span id="modalDate"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const baseUrl = 'api.php'; 
        let currentMovie = null; 
        let favorites = JSON.parse(localStorage.getItem('netfilmes_favs')) || [];

        // Função Genérica para carregar dados (Usada pela busca e pelos filtros)
        async function fetchMovies(query = '') {
            // Atualiza Título para padrão se for busca
            if(query) document.getElementById('sectionTitle').innerText = `Resultados para: "${query}"`;
            else document.getElementById('sectionTitle').innerText = 'Destaques';

            const endpoint = query 
                ? `?endpoint=search/movie&query=${encodeURIComponent(query)}` 
                : `?endpoint=movie/popular`;

            loadData(endpoint);
        }

        // --- MELHORIA: Função específica para categorias (Filtros) ---
        function fetchCategory(categoryEndpoint, title) {
            document.getElementById('searchInput').value = ''; // Limpa busca
            document.getElementById('sectionTitle').innerText = title; // Atualiza título (ex: Em Breve)
            
            // Chama a função principal passando o endpoint da categoria
            loadData(`?endpoint=${categoryEndpoint}`);
        }

        // Função central que faz a requisição e gerencia o SPINNER
        async function loadData(queryString) {
            // 1. UI: Mostra Spinner, Esconde Carrossel
            document.getElementById('loadingSpinner').classList.remove('d-none');
            document.getElementById('carouselContainer').classList.add('d-none');

            try {
                const response = await fetch(`${baseUrl}${queryString}`);
                
                if (!response.ok) throw new Error('Erro na API Local');
                const data = await response.json();
                
                if (data.results && data.results.length > 0) {
                    displayMovies(data.results);
                } else {
                    alert('Nenhum filme encontrado!');
                }

            } catch (error) {
                console.error('Erro:', error);
            } finally {
                // 2. UI: Esconde Spinner, Mostra Carrossel (independente de erro)
                document.getElementById('loadingSpinner').classList.add('d-none');
                document.getElementById('carouselContainer').classList.remove('d-none');
            }
        }

        function displayMovies(movies) {
            const track = document.getElementById('carouselTrack');
            track.innerHTML = ''; 
            
            movies.forEach(movie => {
                const poster = movie.poster_path 
                    ? `https://image.tmdb.org/t/p/w500${movie.poster_path}` 
                    : 'https://placehold.co/400x600?text=Sem+Imagem';
                
                track.innerHTML += `
                    <div class="card card-movie">
                        <img src="${poster}" class="card-img-top" alt="${movie.title}">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-truncate" title="${movie.title}">${movie.title}</h5>
                            <button class="btn btn-danger btn-sm mt-auto" onclick="showMovieDetails(${movie.id})">Ver Detalhes</button>
                        </div>
                    </div>`;
            });
        }

        async function showMovieDetails(movieId) {
            try {
                const response = await fetch(`${baseUrl}?endpoint=movie/${movieId}`);
                if (!response.ok) throw new Error('Erro ao carregar detalhes');

                currentMovie = await response.json();

                document.getElementById('modalTitle').innerText = currentMovie.title;
                document.getElementById('modalOverview').innerText = currentMovie.overview || "Sem sinopse.";
                document.getElementById('modalRating').innerText = currentMovie.vote_average.toFixed(1);
                document.getElementById('modalDate').innerText = currentMovie.release_date;
                
                const poster = currentMovie.poster_path 
                    ? `https://image.tmdb.org/t/p/w500${currentMovie.poster_path}`
                    : 'https://placehold.co/400x600?text=Sem+Imagem';
                document.getElementById('modalPoster').src = poster;

                updateFavoriteButton();
                new bootstrap.Modal(document.getElementById('movieModal')).show();
            } catch (error) {
                console.error(error);
                alert('Não foi possível carregar os detalhes.');
            }
        }

        function updateFavoriteButton() {
            const isFav = favorites.some(f => f.id === currentMovie.id);
            const btn = document.getElementById('btnFav');
            btn.innerText = isFav ? 'Remover Favorito' : 'Favoritar ★';
            btn.className = isFav ? 'btn btn-warning btn-sm' : 'btn btn-outline-warning btn-sm';
            btn.onclick = toggleFavorite;
        }

        function toggleFavorite() {
            const index = favorites.findIndex(f => f.id === currentMovie.id);
            if (index > -1) {
                favorites.splice(index, 1);
            } else {
                favorites.push({
                    id: currentMovie.id,
                    title: currentMovie.title,
                    poster_path: currentMovie.poster_path
                });
            }
            localStorage.setItem('netfilmes_favs', JSON.stringify(favorites));
            renderFavorites();
            updateFavoriteButton();
        }

        function renderFavorites() {
            const container = document.getElementById('favoritesContainer');
            const section = document.getElementById('favSection');

            if (favorites.length === 0) {
                section.style.display = 'none';
                return;
            }

            section.style.display = 'block';
            container.innerHTML = '';
            favorites.forEach(movie => {
                const poster = movie.poster_path 
                    ? `https://image.tmdb.org/t/p/w500${movie.poster_path}` 
                    : 'https://placehold.co/400x600?text=Sem+Imagem';

                container.innerHTML += `
                    <div class="card card-movie card-fav">
                        <img src="${poster}" class="card-img-top">
                        <div class="card-body">
                            <h6 class="text-truncate">${movie.title}</h6>
                            <button class="btn btn-outline-danger btn-sm w-100" onclick="showMovieDetails(${movie.id})">Ver</button>
                        </div>
                    </div>`;
            });
        }

        function searchMovie() {
            const term = document.getElementById('searchInput').value;
            if (term.trim()) fetchMovies(term);
        }

        function moveCarousel(direction) {
            const container = document.getElementById('carouselContainer');
            const scrollAmount = container.clientWidth * 0.7;

            if (direction === 'next') {
                container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            } else {
                container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            }
        }

        document.getElementById('nextBtn').addEventListener('click', () => moveCarousel('next'));
        document.getElementById('prevBtn').addEventListener('click', () => moveCarousel('prev'));
        
        document.getElementById('searchInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') searchMovie();
        });

        fetchMovies();
        renderFavorites();
    </script>
</body>

</html>