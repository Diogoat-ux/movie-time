<?php
require_once __DIR__ . '/../models/MovieModel.php';
require_once __DIR__ . '/../models/SeriesModel.php';

class IndexController {

    public function index() {
        $movieModel = new MovieModel();
        $seriesModel = new SeriesModel();

        // Vérifie si une recherche a été faite
        $query = isset($_GET['query']) ? filter_input(INPUT_GET, 'query', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';

        if (!empty($query)) {
            $movies = $movieModel->search($query);
            $series = $seriesModel->search($query);
        } else {
            $movies = $movieModel->getPopularMovies();
            $series = $seriesModel->getPopularSeries();
        }

        // Vérification que les résultats sont bien des tableaux
        if (!is_array($popularMovies)) {
            $popularMovies = [];
        }
        if (!is_array($popularSeries)) {
            $popularSeries = [];
        }

        include __DIR__ . '/../views/index.php';
    }

    public function search() {
        $query = filter_input(INPUT_GET, 'query', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $this->index($query);
    }
}
?>
