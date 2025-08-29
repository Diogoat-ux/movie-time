<?php
require_once __DIR__ . '/../config/ConnectionParam.php';

class MovieModel {
    private $apiKey;
    private $baseUrl;

    public function __construct() {
        $this->apiKey  = TMDB_API_KEY;
        $this->baseUrl = 'https://api.themoviedb.org/3';
    }

    public function getPopularMovies() {
        $movies = [];

        for ($page = 1; $page <= 3; $page++) {
            $url = $this->baseUrl . "/movie/popular?api_key=" . $this->apiKey . "&language=fr-FR&page=" . $page;
            $data = $this->fetchData($url);

            if (!empty($data->results)) {
                $movies = array_merge($movies, $data->results);
            }

            if (count($movies) >= 50) {
                break;
            }
        }

        return array_slice($movies, 0, 50);
    }

    public function getDetails($id) {
        $url = $this->baseUrl . "/movie/$id?api_key=" . $this->apiKey . '&language=fr-FR';
        $data = $this->fetchData($url);

        if ($data) {
            $data->releaseYear = isset($data->release_date) ? date("Y", strtotime($data->release_date)) : 'Non renseignée';
        }
        return $data;
    }

    public function search($query) {
        $url = $this->baseUrl . "/search/movie?api_key=" . $this->apiKey . '&language=fr-FR&query=' . urlencode($query);
        $data = $this->fetchData($url);
        return isset($data->results) ? $data->results : [];
    }    
    

    public function filterByGenre($genreId) {
        $movies = [];
    
        for ($page = 1; $page <= 3; $page++) {
            $url = $this->baseUrl . "/discover/movie?api_key=" . $this->apiKey . "&language=fr-FR&with_genres=" . $genreId . "&page=" . $page;
            $data = $this->fetchData($url);
    
            if (!empty($data->results)) {
                $movies = array_merge($movies, $data->results);
            }
    
            if (count($movies) >= 50) {
                break;
            }
        }
    
        return array_slice($movies, 0, 50);
    }
    

    public function getCredits($id) {
        $url = $this->baseUrl . "/movie/$id/credits?api_key=" . $this->apiKey . '&language=fr-FR';
        return $this->fetchData($url);
    }

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

    private function parseResults($data) {
        if (is_array($data)) {
            return isset($data['results']) ? $data['results'] : [];
        } elseif (is_object($data)) {
            return isset($data->results) ? $data->results : [];
        }
        return [];
    }

    public function createListWithMovie($idMovie, $idUser, $name)
    {
        //create a list
        $query = "INSERT INTO mt_lists (ID_USER, NAME_LIST) 
        VALUES (:idUser, :name)";
        $stmt = Database::getInstance()->prepare($query);
        $stmt->bindParam(":idUser", $idUser);
        $stmt->bindParam(":name", $name);
        $stmt->execute();
        $idList = Database::getInstance()->lastInsertId();

        //create a movie
        $query2 = "INSERT INTO mt_movies (ID_MOVIE_EXTERNAL) 
        VALUES (:idMovie)";
        $stmt2 = Database::getInstance()->prepare($query2);
        $stmt2->bindParam(":idMovie", $idMovie);
        $stmt2->execute();
        $idMovies = Database::getInstance()->lastInsertId();

        //add the movie to the list
        $query3 = "INSERT INTO mt_movie_lists (ID_MOVIES, ID_LISTS) 
        VALUES (:idMovie, :idList)";
        $stmt3 = Database::getInstance()->prepare($query3);
        $stmt3->bindParam(":idMovie", $idMovies);
        $stmt3->bindParam(":idList", $idList);

        return $stmt3->execute();
    }


    function getOrCreateMovieId($idMovie)
    {
        // Check if the movie already exists in the database
        $query = "SELECT ID_MOVIES FROM mt_movies WHERE ID_MOVIE_EXTERNAL = :idMovie";
        $stmt = Database::getInstance()->prepare($query);
        $stmt->bindParam(":idMovie", $idMovie);
        $stmt->execute();
        $movie = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($movie) {
            return $movie['ID_MOVIES']; // Return the existing movie ID
        }
    
        // Insert the movie if it does not exist
        $query2 = "INSERT INTO mt_movies (ID_MOVIE_EXTERNAL) VALUES (:idMovie)";
        $stmt2 = Database::getInstance()->prepare($query2);
        $stmt2->bindParam(":idMovie", $idMovie);
        $stmt2->execute();
    
        return Database::getInstance()->lastInsertId(); // Return the newly created movie ID
    }

    public function addMovieToList($idMovie, $idList)
    {
        // Get or create the movie ID
        $idMovieBDD = $this->insertMovie($idMovie);
    
        // Check if the movie is already in the list
        $query = "SELECT COUNT(*) FROM mt_movie_lists WHERE ID_MOVIES = :idMovieBDD AND ID_LISTS = :idList";
        $stmt = Database::getInstance()->prepare($query);
        $stmt->bindParam(":idMovieBDD", $idMovieBDD);
        $stmt->bindParam(":idList", $idList);
        $stmt->execute();
    
        if ($stmt->fetchColumn() > 0) {
            return false; // Movie is already in the list, do nothing
        }
    
        // Insert the movie into the list
        $query2 = "INSERT INTO mt_movie_lists (ID_MOVIES, ID_LISTS) VALUES (:idMovieBDD, :idList)";
        $stmt2 = Database::getInstance()->prepare($query2);
        $stmt2->bindParam(":idMovieBDD", $idMovieBDD);
        $stmt2->bindParam(":idList", $idList);
    
        return $stmt2->execute(); // Return true if the insertion was successful
    }
    



    public function insertMovie($idMovie){
        $idInsertMovie = null;
        $query = "SELECT ID_MOVIES FROM mt_movies WHERE ID_MOVIE_EXTERNAL = :idMovie";
        $stmt = Database::getInstance()->prepare($query);
        $stmt->bindParam(":idMovie", $idMovie);
        $stmt->execute();
        $idInsertMovie = $stmt->fetchColumn();
        if($idInsertMovie == null){
            $query2 = "INSERT INTO mt_movies (ID_MOVIE_EXTERNAL) 
            VALUES (:idMovie)";
            $stmt2 = Database::getInstance()->prepare($query2);
            $stmt2->bindParam(":idMovie", $idMovie);
            if($stmt2->execute()){
                $idInsertMovie = Database::getInstance()->lastInsertId();
            }
        }
        return $idInsertMovie;
    }

    public function getMovie($idMovie){
        $query = "SELECT * FROM mt_movies WHERE ID_MOVIE_EXTERNAL = :idMovie";
        $stmt = Database::getInstance()->prepare($query);
        $stmt->bindParam(":idMovie", $idMovie);
        $stmt->execute();
        return $stmt->fetch();
    }


    public function displayLists($userId) {
        $query = "SELECT * FROM mt_lists WHERE ID_USER = :userId";
        $stmt = Database::getInstance()->prepare($query);
        $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function getMovies($idList) {
        $query = "SELECT mt_movies.ID_MOVIES, mt_movies.ID_MOVIE_EXTERNAL, mt_movie_lists.ID_MOVIES AS list_movie_id
                  FROM mt_movie_lists
                  JOIN mt_movies ON mt_movies.ID_MOVIES = mt_movie_lists.ID_MOVIES
                  WHERE ID_LISTS = :idList";
    
        $stmt = Database::getInstance()->prepare($query);
        $stmt->bindParam(":idList", $idList, PDO::PARAM_INT);
        $stmt->execute();
        $moviesInList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $result = [];
    
        $movieModel = new self();
        foreach ($moviesInList as $movieRow) {
            $externalId = $movieRow['ID_MOVIE_EXTERNAL'];
            $details = $movieModel->getDetails($externalId, 'movie');
            if ($details && !empty($details->title)) {
                // Ajouter list_movie_id dans la réponse
                $details->list_movie_id = $movieRow['list_movie_id'];
                $result[] = $details;
            }
        }
    
        return $result;
    }
    

    public function deleteList($idList)
    {
        $query = "DELETE FROM mt_movie_lists WHERE ID_LISTS = :idList";

        $stmt = Database::getInstance()->prepare($query);
        $stmt->bindParam(":idList", $idList, PDO::PARAM_INT); 
        $stmt->execute(); 

        $query = "DELETE FROM mt_lists WHERE ID_LISTS = :idList";
        $stmt = Database::getInstance()->prepare($query);
        $stmt->bindParam(":idList", $idList, PDO::PARAM_INT); 
        $stmt->execute();

        return;
    }

    public function getUserRating($idUser, $idMovie) {
        $query = "SELECT RATE FROM mt_rates WHERE ID_USER = :idUser AND ID_MOVIES = :idMovie";
        $stmt = Database::getInstance()->prepare($query);
        $stmt->bindParam(":idUser", $idUser, PDO::PARAM_INT);
        $stmt->bindParam(":idMovie", $idMovie, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['RATE'] : null;
    }
    
    public function insertUserRating($idUser, $idMovie, $rating) {
        // Check if the rating already exists for the user and movie
        $query = "SELECT COUNT(*) FROM mt_rates WHERE ID_USER = :idUser AND ID_MOVIES = :idMovie";
        $stmt = Database::getInstance()->prepare($query);
        $stmt->bindParam(":idUser", $idUser, PDO::PARAM_INT);
        $stmt->bindParam(":idMovie", $idMovie, PDO::PARAM_INT);
        $stmt->execute();
        $exists = $stmt->fetchColumn();
    
        if ($exists) {
            // Update the rating if it already exists
            $query = "UPDATE mt_rates SET RATE = :rating WHERE ID_USER = :idUser AND ID_MOVIES = :idMovie";
            $stmt = Database::getInstance()->prepare($query);
        } else {
            // Insert the rating if it does not exist
            $query = "INSERT INTO mt_rates (ID_USER, ID_MOVIES, RATE) VALUES (:idUser, :idMovie, :rating)";
            $stmt = Database::getInstance()->prepare($query);
        }
    
        $stmt->bindParam(":idUser", $idUser, PDO::PARAM_INT);
        $stmt->bindParam(":idMovie", $idMovie, PDO::PARAM_INT);
        $stmt->bindParam(":rating", $rating, PDO::PARAM_INT);
    
        return $stmt->execute();
    }


    public function deleteMovie($movieID, $listID) {
        $query = "DELETE FROM mt_movie_lists WHERE ID_LISTS = :idList AND ID_MOVIES = :idMovie";

        $stmt = Database::getInstance()->prepare($query);
        $stmt->bindParam(":idList", $listID, PDO::PARAM_INT); 
        $stmt->bindParam(":idMovie", $movieID, PDO::PARAM_INT); 
                
    
        return $stmt->execute();
    }

    public function deleteUserRating($idUser, $idMovieExternal) {
        $result = $this->getMovie($idMovieExternal);
        $idMovie = $result["ID_MOVIES"];
        $query = "DELETE FROM mt_rates WHERE ID_USER = :idUser AND ID_MOVIES = :idMovie";
        $stmt = Database::getInstance()->prepare($query);
        $stmt->bindParam(":idUser", $idUser, PDO::PARAM_INT);
        $stmt->bindParam(":idMovie", $idMovie, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
}
