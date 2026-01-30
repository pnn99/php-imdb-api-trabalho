<?php

// 1. Definições de Segurança
$apiKey = 'c7fc658736c0c3cb216a06959a6dfc49'; 
$baseUrl = 'https://api.themoviedb.org/3';

// Define que a resposta será sempre JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Permite acesso local

// 2. Captura os parâmetros enviados pelo Front-end
$endpoint = $_GET['endpoint'] ?? null;
$query    = $_GET['query'] ?? null;

// Validação simples
if (!$endpoint) {
    http_response_code(400);
    echo json_encode(['error' => 'Endpoint não especificado.']);
    exit;
}

// 3. Montagem da URL Real (TMDb)
// URL Base + Endpoint Desejado + Chave de API + Configs Extras
$url = "$baseUrl/$endpoint?api_key=$apiKey&language=pt-BR";

// Se tiver uma busca (query), adiciona na URL
if ($query) {
    $url .= "&query=" . urlencode($query);
}

// 4. Faz a requisição para a TMDb (usando cURL)
// O cURL é mais robusto que file_get_contents para APIs
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignora verificação SSL local (bom para dev)
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// 5. Devolve a resposta exata da TMDb para o seu Front-end
http_response_code($httpCode);
echo $response;
?>