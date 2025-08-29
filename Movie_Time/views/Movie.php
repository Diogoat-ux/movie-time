<?php
require_once __DIR__ . '/../models/MovieModel.php';
$movieModel = new MovieModel();

// Checking the applied filter
$selectedGenre = isset($_GET['genre']) ? $_GET['genre'] : "";

// Retrieving popular or filtered films
if (!empty($selectedGenre)) {
    $movies = $movieModel->filterByGenre($selectedGenre);
} else {
    $movies = $movieModel->getPopularMovies();
}

include __DIR__ . '/header.php';

// Title retrieval help function
function getTitle($item) {
    return !empty($item->title) ? $item->title : (!empty($item->name) ? $item->name : 'Titre inconnu');
}
?>

<!-- Back button Home -->
<a href="index.php" class="btn-classique">Retour Accueil</a>

<!-- Film filter section -->
<section class="section">
    <h2>Filtrer par Genre (Films)</h2>
    <div class="filter-container">
        <div class="filter-dropdown">
            <h3>Genre</h3>
            <select id="genreSelect" class="styled-select" onchange="filterGenre()">
                <option value="">-- Choisir un genre --</option>
                <option value="28" <?php echo ($selectedGenre == "28") ? "selected" : ""; ?>>Action</option>
                <option value="12" <?php echo ($selectedGenre == "12") ? "selected" : ""; ?>>Aventure</option>
                <option value="16" <?php echo ($selectedGenre == "16") ? "selected" : ""; ?>>Animation</option>
                <option value="35" <?php echo ($selectedGenre == "35") ? "selected" : ""; ?>>Comédie</option>
                <option value="80" <?php echo ($selectedGenre == "80") ? "selected" : ""; ?>>Crime</option>
                <option value="99" <?php echo ($selectedGenre == "99") ? "selected" : ""; ?>>Documentaire</option>
                <option value="18" <?php echo ($selectedGenre == "18") ? "selected" : ""; ?>>Drame</option>
                <option value="10751" <?php echo ($selectedGenre == "10751") ? "selected" : ""; ?>>Familial</option>
                <option value="14" <?php echo ($selectedGenre == "14") ? "selected" : ""; ?>>Fantastique</option>
                <option value="27" <?php echo ($selectedGenre == "27") ? "selected" : ""; ?>>Horreur</option>
                <option value="10749" <?php echo ($selectedGenre == "10749") ? "selected" : ""; ?>>Romance</option>
                <option value="878" <?php echo ($selectedGenre == "878") ? "selected" : ""; ?>>Science-fiction</option>
                <option value="53" <?php echo ($selectedGenre == "53") ? "selected" : ""; ?>>Thriller</option>
                <option value="36" <?php echo ($selectedGenre == "36") ? "selected" : ""; ?>>Histoire</option>
            </select>
        </div>
    </div>
</section>


<!-- Movie Display -->
<section class="section">
    <h2><?php echo (!empty($selectedGenre)) ? "Films filtrés" : "Films Populaires"; ?></h2>
    <div class="movies-grid">
        <?php if (!empty($movies)): ?>
            <?php foreach ($movies as $movie): ?>
                <div class="movie-card">
                    <a href="../public/Router.php?controller=movie&action=details&id=<?php echo $movie->id; ?>&type=movie">
                        <img src="https://image.tmdb.org/t/p/w300<?php echo $movie->poster_path ?: '/public/no-image.jpg'; ?>" alt="<?php echo htmlspecialchars(getTitle($movie)); ?>">
                        <div class="movie-info">
                            <h3 style="color: white;"><?php echo htmlspecialchars(getTitle($movie)); ?></h3>
                            <p style="color: white;">Note: <?php echo htmlspecialchars($movie->vote_average); ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color: white;">Aucun film trouvé.</p>
        <?php endif; ?>
    </div>
</section>

<script>
function filterGenre() {
    var genre = document.getElementById('genreSelect').value;
    if (genre !== "") {
        window.location.href = "movie.php?genre=" + genre;
    }
}
</script>

<?php include __DIR__ . '/footer.php'; ?>
