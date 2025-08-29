<?php
require_once __DIR__ . '/../config/ConnectionParam.php';

class SeriesModel {

    private $apiKey;
    private $baseUrl;

    public function __construct() {
        $this->apiKey  = TMDB_API_KEY;
        $this->baseUrl = 'https://api.themoviedb.org/3';
    }

    public function getPopularSeries() {
      $series = [];
  
      for ($page = 1; $page <= 3; $page++) {
          $url = $this->baseUrl . "/tv/popular?api_key=" . $this->apiKey . "&language=fr-FR&page=" . $page;
          $data = $this->fetchData($url);
  
          if (!empty($data->results)) {
              $series = array_merge($series, $data->results);
          }
  
          // Si on a déjà 50 séries, on arrête
          if (count($series) >= 50) {
              break;
          }
      }
  
      return array_slice($series, 0, 50); 
  }
  
  

    public function getDetails($id) {
        $url = $this->baseUrl . "/tv/$id?api_key=" . $this->apiKey . '&language=fr-FR';
        $data = $this->fetchData($url);

        if ($data) {
            $data->releaseYear = isset($data->first_air_date) ? date("Y", strtotime($data->first_air_date)) : 'Non renseignée';
        }
        return $data;
    }

    public function search($query) {
        $url = $this->baseUrl . "/search/tv?api_key=" . $this->apiKey . '&language=fr-FR&query=' . urlencode($query);
        $data = $this->fetchData($url);
        return isset($data->results) ? $data->results : [];
    }    
    

    public function filterByGenre($genreId) {
      $series = [];
  
      for ($page = 1; $page <= 3; $page++) {
          $url = $this->baseUrl . "/discover/tv?api_key=" . $this->apiKey . "&language=fr-FR&with_genres=" . $genreId . "&page=" . $page;
          $data = $this->fetchData($url);
  
          if (!empty($data->results)) {
              $series = array_merge($series, $data->results);
          }
  
          if (count($series) >= 50) {
              break;
          }
      }
  
      return array_slice($series, 0, 50);
  }
  

    public function getCredits($id) {
        $url = $this->baseUrl . "/tv/$id/credits?api_key=" . $this->apiKey . '&language=fr-FR';
        return $this->fetchData($url);
    }

    // 🔥 Méthode privée pour récupérer et décoder les données JSON depuis l’API
    private function fetchData($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);

        if (curl_errno($ch)) {
            echo '<p style="color:red;">Erreur API : ' . curl_error($ch) . '</p>';
        }
        curl_close($ch);
        return json_decode($output);
    }

    private function parseResults($data, $limit = null) {
        if (isset($data->results) && is_array($data->results)) {
            return $limit ? array_slice($data->results, 0, $limit) : $data->results;
        }
        return [];
    }
}
