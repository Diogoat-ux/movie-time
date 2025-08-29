<?php
require_once __DIR__ . '/../models/MovieModel.php';

class MovieController {

    // Filter films by genre
    public function filter($genre) {
        $movieModel = new MovieModel();
        
        // Récupération des films filtrés
        $movies = $movieModel->filterByGenre($genre);
        // Envelopper les résultats dans un objet
        $filteredMovies = (object)['results' => $movies];

        include __DIR__ . '/../views/movie.php';
    }

    // Viewing movie details
    public function details($id) {
        $movieModel = new MovieModel();
        $details = $movieModel->getDetails($id);
        $credits = $movieModel->getCredits($id);
        include __DIR__ . '/../views/details.php';
    }

// Create a list containing a film
public function createListWithMovie($idMovie, $nameList) {
    session_start();
    $userId = $_SESSION['user']['id'] ?? null;
    if (!$userId) {
        echo "Veuillez vous connecter pour créer une liste.";
        return;
    }
    $movieModel = new MovieModel();
    $movieModel->createListWithMovie($idMovie, $userId, $nameList);
}


    public function displayLists() {
        session_start(); 
        if (!isset($_SESSION['user']['id'])) {
            echo json_encode([]); 
            return;
        }
        $userId = $_SESSION['user']['id'];
        $movieModel = new MovieModel();
        $lesListes = $movieModel->displayLists($userId);
        echo json_encode($lesListes);
    }
    

    // Retrieving films from a list
    public function getMovies($idList) {
        $movieModel = new MovieModel();
        $lesFilms = $movieModel->getMovies($idList);
        echo json_encode($lesFilms);
    }

    // Deleting a list
    public function deleteList($idList) {
        $movieModel = new MovieModel();
        $movieModel->deleteList($idList);
    }

    public function addMovieToList($idMovie, $idList) {
        $movieModel = new MovieModel();
        $movieModel->addMovieToList($idMovie, $idList);
    }
    // Recover the user rating for a film
public function getUserRating() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        session_start(); 
        $idUser = $_SESSION['user']['id'] ?? null;
        $idMovie = filter_input(INPUT_GET, 'idMovieExternal', FILTER_SANITIZE_NUMBER_INT);
    
        if ($idUser && $idMovie) {
            $movieModel = new MovieModel();
            $movie = $movieModel->getMovie($idMovie);
            if ($movie) {
                $rating = $movieModel->getUserRating($idUser, $movie['ID_MOVIES']);
                echo json_encode(["success" => true, "rating" => $rating]);
            } else {
                echo json_encode(["success" => false, "message" => "Film non trouvé."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Données invalides."]);
        }
    }
}

// Inserting a user note for a film
public function insertUserRating(){
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        session_start(); 
        $idUser = $_SESSION['user']['id'] ?? null;
        $idMovieExternal = filter_input(INPUT_GET, 'idMovieExternal', FILTER_SANITIZE_NUMBER_INT);
        $rate = filter_input(INPUT_GET, 'rating', FILTER_SANITIZE_NUMBER_INT);
    
        if ($idUser && $idMovieExternal && $rate) {
            $movieModel = new MovieModel();
            $idMovie = $movieModel->insertMovie($idMovieExternal);
            if($idMovie != null){
                $rateResult = $movieModel->insertUserRating($idUser, $idMovie, $rate);
                echo json_encode(["success" => true, "rating" => $rateResult]);
            } else {
                echo json_encode(["success" => false, "message" => "Erreur lors de l'insertion du film."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Données invalides."]);
        }
    }
}

public function deleteUserRating() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        session_start(); 
        $idUser = $_SESSION['user']['id'] ?? null;
        $idMovieExternal = filter_input(INPUT_GET, 'idMovieExternal', FILTER_SANITIZE_NUMBER_INT);
    
        if ($idUser && $idMovieExternal) {
            $movieModel = new MovieModel();
            $movie = $movieModel->getMovie($idMovieExternal);
            if (!$movie) {
                echo json_encode(["success" => false, "message" => "Film non trouvé."]);
                return;
            }
            $idMovie = $movie['ID_MOVIES'];
            $success = $movieModel->deleteUserRating($idUser, $idMovie);
            echo json_encode(["success" => $success]);
        } else {
            echo json_encode(["success" => false, "message" => "Données invalides."]);
        }
    }
}





    // Deleting a list
    public function deleteMovie($movieID, $listID) {
        $movieModel = new MovieModel();
        $movieModel->deleteMovie($movieID, $listID);
    }
}
?>
