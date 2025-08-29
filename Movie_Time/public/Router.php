<?php
// Autoloading classes in controllers, models and config folders
spl_autoload_register(function ($class_name) {
    $paths = [
        __DIR__ . '/../controllers/' . $class_name . '.php',
        __DIR__ . '/../models/' . $class_name . '.php',
        __DIR__ . '/../config/' . $class_name . '.php'
    ];
    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Securing parameters with filter_input
$controller = filter_input(INPUT_GET, 'controller', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: 'index';
$action     = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: 'index';
$id         = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$type       = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: 'movie';
$genre      = filter_input(INPUT_GET, 'genre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$idList     = filter_input(INPUT_GET, 'list', FILTER_VALIDATE_INT);
$idMovie    = filter_input(INPUT_GET, 'idMovie', FILTER_VALIDATE_INT);
$nameList   = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$idSeries   = filter_input(INPUT_GET, 'idSeries', FILTER_VALIDATE_INT);

$idListt    = filter_input(INPUT_GET, 'idList', FILTER_VALIDATE_INT);
$listID     = filter_input(INPUT_GET, 'listid', FILTER_VALIDATE_INT);
$movieID    = filter_input(INPUT_GET, 'movieid', FILTER_VALIDATE_INT);

try {
    switch ($controller) {
        case 'index':
            if (!class_exists('IndexController')) {
                throw new Exception("🚨 Erreur : `IndexController` introuvable !");
            }
            $controllerObj = new IndexController();
            if ($action === 'search') {
                $query = filter_input(INPUT_GET, 'query', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                header("Location: ../views/index.php?query=" . urlencode($query));
                exit();
            } else {
                $controllerObj->index();
            }
            break;
            
        case 'movie':
            if (!class_exists('MovieController')) {
                throw new Exception("🚨 Erreur : `MovieController` introuvable !");
            }
            $controllerObj = new MovieController();
            switch ($action) {
                case 'details':
                    $controllerObj->details($id);
                    break;
                case 'filter':
                    $controllerObj->filter($genre);
                    break;
                case 'getMovies':
                    $controllerObj->getMovies($idList);
                    break;
                case 'deleteList':
                    $controllerObj->deleteList($idList);
                    break;
                case 'addMovieToList':
                    $controllerObj->addMovieToList($idMovie, $idListt); 
                    break;
                case 'createListWithMovie':
                    $controllerObj->createListWithMovie($idMovie, $nameList);
                    break;
                case 'displayLists':
                    $controllerObj->displayLists();
                    break;
                case 'insertUserRating':
                    $controllerObj->insertUserRating();
                    break;
                case 'deleteMovie':
                    $controllerObj->deleteMovie($movieID, $listID);
                    break;
                case 'getUserRating':
                    $controllerObj->getUserRating();
                    break;

                case 'deleteUserRating':
                    $controllerObj->deleteUserRating();
                    break;
                
                default:
                    throw new Exception("⚠️ Action `$action` inconnue pour `MovieController`.");
            }
            break;
            
        case 'series':
            if (!class_exists('SeriesController')) {
                throw new Exception("🚨 Erreur : `SeriesController` introuvable !");
            }
            $controllerObj = new SeriesController();
            switch ($action) {
                case 'details':
                    $controllerObj->details($id);
                    break;
                case 'filter':
                    $controllerObj->filter($genre);
                    break;
                case 'getSeries':
                    $controllerObj->getSeries($idList);
                    break;
                case 'deleteList':
                    $controllerObj->deleteList($idList);
                    break;
                case 'addSeriesToList':
                    $controllerObj->addSeriesToList($idList, $idSeries);
                    break;
                case 'createListWithSeries':
                    $controllerObj->createListWithSeries($idSeries, $nameList);
                    break;
                default:
                    throw new Exception("⚠️ Action `$action` inconnue pour `SeriesController`.");
            }
            break;
            
        case 'user':
            if (!class_exists('UserController')) {
                throw new Exception("🚨 Erreur : `UserController` introuvable !");
            }
            $controllerObj = new UserController();
            switch ($action) {
                case 'logout':
                    $controllerObj->logout();
                    break;
                // Vous pouvez ajouter d'autres actions pour le contrôleur "user" ici
                default:
                    throw new Exception("⚠️ Action `$action` inconnue pour `UserController`.");
            }
            break;
            
        default:
            throw new Exception("❌ Contrôleur `$controller` non trouvé.");
    }
} catch (Exception $e) {
    echo "<h2 style='color: red;'>Erreur :</h2><p>{$e->getMessage()}</p>";
}
?>
