<?php
require_once __DIR__ . '/../models/SeriesModel.php';
$seriesModel = new SeriesModel();

// Vérifier si un genre a été sélectionné
$selectedGenre = isset($_GET['genre']) ? $_GET['genre'] : null;

if ($selectedGenre) {
    // Si un genre est sélectionné, récupérer les séries filtrées
    $popularSeries = $seriesModel->filterByGenre($selectedGenre);
} else {
    // Sinon, récupérer les 50 séries populaires
    $popularSeries = $seriesModel->getPopularSeries();
}

include __DIR__ . '/header.php';

// Vérification de la fonction getTitle()
function getTitle($item) {
    return !empty($item->name) ? $item->name : 'Titre inconnu';
}
?>

<!-- Bouton Retour Accueil -->
<a href="index.php" class="btn-classique">Retour Accueil</a>

<!-- Section Filtre Séries -->
<section class="section">
    <h2>Filtrer par Genre (Séries)</h2>
    <div class="filter-container">
        <div class="filter-dropdown">
            <h3>Genre</h3>
            <select id="genreSelectSeries" class="styled-select" onchange="filterGenreSeries()">
                <option value="">-- Choisir un genre --</option>
                <option value="10759" <?php echo ($selectedGenre == "10759") ? "selected" : ""; ?>>Action et aventure</option>
                <option value="16" <?php echo ($selectedGenre == "16") ? "selected" : ""; ?>>Animation</option>
                <option value="35" <?php echo ($selectedGenre == "35") ? "selected" : ""; ?>>Comédie</option>
                <option value="80" <?php echo ($selectedGenre == "80") ? "selected" : ""; ?>>Crime</option>
                <option value="99" <?php echo ($selectedGenre == "99") ? "selected" : ""; ?>>Documentaire</option>
                <option value="18" <?php echo ($selectedGenre == "18") ? "selected" : ""; ?>>Drame</option>
                <option value="10751" <?php echo ($selectedGenre == "10751") ? "selected" : ""; ?>>Familial</option>
                <option value="14" <?php echo ($selectedGenre == "14") ? "selected" : ""; ?>>Fantastique</option>
                <option value="27" <?php echo ($selectedGenre == "27") ? "selected" : ""; ?>>Horreur</option>
                <option value="10765" <?php echo ($selectedGenre == "10765") ? "selected" : ""; ?>>Science-fiction et fantasy</option>
                <option value="9648" <?php echo ($selectedGenre == "9648") ? "selected" : ""; ?>>Mystère</option>
                <option value="10767" <?php echo ($selectedGenre == "10767") ? "selected" : ""; ?>>Talk-show</option>
                <option value="10764" <?php echo ($selectedGenre == "10764") ? "selected" : ""; ?>>Réalité</option>
            </select>
        </div>
    </div>
</section>

<!-- Affichage des Séries -->
<section class="section">
    <h2><?php echo ($selectedGenre) ? "Séries filtrées" : "Séries Populaires"; ?></h2>
    <div class="movies-grid">
        <?php foreach ($popularSeries as $series): ?>
            <div class="movie-card">
                <a href="../public/Router.php?controller=series&action=details&id=<?php echo $series->id; ?>&type=tv">
                    <img src="https://image.tmdb.org/t/p/w300<?php echo $series->poster_path ?: '/public/no-image.jpg'; ?>" alt="<?php echo htmlspecialchars(getTitle($series)); ?>">
                    <div class="movie-info">
                        <h3 style="color: white;"><?php echo htmlspecialchars(getTitle($series)); ?></h3>
                        <p style="color: white;">Note: <?php echo htmlspecialchars($series->vote_average); ?></p>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<script>
function filterGenreSeries() {
    var genre = document.getElementById('genreSelectSeries').value;
    if (genre !== "") {
        window.location.href = "series.php?genre=" + genre;
    }
}
</script>

<?php include __DIR__ . '/footer.php'; ?>
