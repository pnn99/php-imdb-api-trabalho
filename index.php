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

        .carousel-section {
            padding: 40px 0 20px 0;
        }

        /* --- MELHORIA: CSS Scroll Snap --- */
        .carousel-track-container {
            display: flex;
            overflow-x: auto; /* Permite rolagem nativa */
            scroll-snap-type: x mandatory; /* Faz o card "travar" na posição correta */
            gap: 15px;
            padding-bottom: 20px;
            width: 95%;
            margin: 0 auto;
            scroll-behavior: smooth; /* Rolagem suave via JS ou clique */
        }
        
        /* Esconde a barra de rolagem (opcional, fica mais bonito) */
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
            /* Removemos o transform manual do JS antigo */
        }

        .card-movie {
            scroll-snap-align: start; /* Ponto de parada do scroll */
            flex: 0 0 auto; /* Impede que o card encolha */
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
    </style>
</head>

<body>

    <div class="banner">
        <h1 class="display-1 fw-bold text-danger">NETFILMES</h1>
    </div>

    <div class="search-container">
        <h3 class="text-center mb-3">Pesquise seu filme favorito</h3>
        <div class="input-group">
            <input type="text" id="searchInput" class="form-control search-input" placeholder="Digite o nome...">
            <button class="btn btn-danger" onclick="searchMovie()">Pesquisar</button>
        </div>
    </div>

    <hr class="border-secondary mx-5">

    <div class="carousel-section">
        <h2 class="px-5 mb-4 text-danger">Destaques</h2>
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
        // --- MELHORIA: Segurança ---
        // A chave de API foi removida daqui. Agora apontamos para nosso backend local.
        const baseUrl = 'api.php'; 
        
        // Removemos o fallback do TVMaze para evitar confusão de dados (Séries vs Filmes)
        // Se quiser reativar, precisará ajustar a lógica, mas para o trabalho, focar no TMDb via proxy é o ideal.

        let currentMovie = null; 
        
        // Inicializar favoritos do LocalStorage
        let favorites = JSON.parse(localStorage.getItem('netfilmes_favs')) || [];

        async function fetchMovies(query = '') {
            // Monta a URL para o nosso proxy PHP
            const endpoint = query 
                ? `?endpoint=search/movie&query=${encodeURIComponent(query)}` 
                : `?endpoint=movie/popular`;

            const targetUrl = `${baseUrl}${endpoint}`;

            try {
                const response = await fetch(targetUrl);
                
                if (!response.ok) {
                    throw new Error('Erro ao comunicar com a API Local');
                }

                const data = await response.json();
                
                if (data.results && data.results.length > 0) {
                    displayMovies(data.results);
                } else {
                    alert('Nenhum filme encontrado!');
                }

            } catch (error) {
                console.error('Erro na busca:', error);
                alert('Erro ao buscar filmes. Verifique se o api.php está configurado corretamente.');
            }
        }

        function displayMovies(movies) {
            const track = document.getElementById('carouselTrack');
            track.innerHTML = ''; // Limpa conteúdo anterior
            
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
                // Chama o proxy passando o ID do filme
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
                alert('Não foi possível carregar os detalhes do filme.');
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

        // --- MELHORIA: Navegação do Carrossel com Scroll ---
        function moveCarousel(direction) {
            const container = document.getElementById('carouselContainer');
            // Rola 70% da largura visível
            const scrollAmount = container.clientWidth * 0.7;

            if (direction === 'next') {
                container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            } else {
                container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            }
        }

        document.getElementById('nextBtn').addEventListener('click', () => moveCarousel('next'));
        document.getElementById('prevBtn').addEventListener('click', () => moveCarousel('prev'));
        
        // Permite pesquisar ao pressionar Enter
        document.getElementById('searchInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') searchMovie();
        });

        // Carregamento inicial
        fetchMovies();
        renderFavorites();
    </script>
</body>

</html>