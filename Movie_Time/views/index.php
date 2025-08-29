<?php
//fin sprint 2
// Inclusion des modèles
require_once __DIR__ . '/../models/MovieModel.php';
require_once __DIR__ . '/../models/SeriesModel.php';

$movieModel = new MovieModel();
$seriesModel = new SeriesModel();

// Check whether a search request has been sent
$query = isset($_GET['query']) ? filter_input(INPUT_GET, 'query', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';

if (!empty($query)) {
    // Movie and series search
    $movies = $movieModel->search($query);
    $series = $seriesModel->search($query);
    echo "<p style='color:yellow;'>🔍 Résultats de recherche pour : <strong>$query</strong></p>";
} else {
    // Display popular content if no search
    $movies = $movieModel->getPopularMovies();
    $series = $seriesModel->getPopularSeries();
}

// Ensure that the variables used in the display actually exist
$popularMovies = $movies ?? [];
$popularSeries = $series ?? [];

include __DIR__ . '/header.php';

// Title retrieval help function
function getTitle($item) {
    return !empty($item->title) ? $item->title : (!empty($item->name) ? $item->name : 'Titre inconnu');
}
?>

<!-- Movies & Series buttons -->
<div class="filter-buttons">
    <button class="btn-classique" onclick="window.location.href='Movie.php'">Films</button>
    <button class="btn-classique" onclick="window.location.href='Series.php'">Séries</button>
</div>

<!-- 🔎 Search results -->
<?php if (!empty($query)) : ?>
    <section class="section">
        <h2>🎬 Résultats Films</h2>
        <div class="movies-grid">
            <?php if (!empty($popularMovies)): ?>
                <?php foreach ($popularMovies as $movie): ?>
                    <div class="movie-card">
                        <a href="../public/Router.php?controller=movie&action=details&id=<?php echo $movie->id; ?>&type=movie">
                            <?php if (!empty($movie->poster_path)): ?>
                                <img src="https://image.tmdb.org/t/p/w300<?php echo $movie->poster_path; ?>" alt="<?php echo htmlspecialchars(getTitle($movie)); ?>">
                            <?php else: ?>
                                <div class="no-image">
                                    <img src="../public/no-image.jpg" alt="Photo Indisponible">
                                </div>
                            <?php endif; ?>
                            <div class="movie-info">
                                <h3 style="color: white;"><?php echo htmlspecialchars(getTitle($movie)); ?></h3>
                                <p style="color: white;">Note: <?php echo htmlspecialchars($movie->vote_average); ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: white;">Aucun film trouvé pour "<strong><?php echo $query; ?></strong>".</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="section">
        <h2>📺 Résultats Séries</h2>
        <div class="movies-grid">
            <?php if (!empty($popularSeries)): ?>
                <?php foreach ($popularSeries as $series): ?>
                    <div class="movie-card">
                        <a href="../public/Router.php?controller=series&action=details&id=<?php echo $series->id; ?>&type=tv">
                            <?php if (!empty($series->poster_path)): ?>
                                <img src="https://image.tmdb.org/t/p/w300<?php echo $series->poster_path; ?>" alt="<?php echo htmlspecialchars(getTitle($series)); ?>">
                            <?php else: ?>
                                <div class="no-image">
                                    <img src="../public/no-image.jpg" alt="Photo Indisponible">
                                </div>
                            <?php endif; ?>
                            <div class="movie-info">
                                <h3 style="color: white;"><?php echo htmlspecialchars(getTitle($series)); ?></h3>
                                <p style="color: white;">Note: <?php echo htmlspecialchars($series->vote_average); ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: white;">Aucune série trouvée pour "<strong><?php echo $query; ?></strong>".</p>
            <?php endif; ?>
        </div>
    </section>
<?php else: ?>
    <!-- Popular films -->
    <section class="section">
        <h2>🎬 Films Populaires</h2>
        <div class="movies-grid">
            <?php foreach ($popularMovies as $movie): ?>
                <div class="movie-card">
                    <a href="../public/Router.php?controller=movie&action=details&id=<?php echo $movie->id; ?>&type=movie">
                        <?php if (!empty($movie->poster_path)): ?>
                            <img src="https://image.tmdb.org/t/p/w300<?php echo $movie->poster_path; ?>" alt="<?php echo htmlspecialchars(getTitle($movie)); ?>">
                        <?php else: ?>
                            <div class="no-image">
                                <img src="../public/no-image.jpg" alt="Photo Indisponible">
                            </div>
                        <?php endif; ?>
                        <div class="movie-info">
                            <h3 style="color: white;"><?php echo htmlspecialchars(getTitle($movie)); ?></h3>
                            <p style="color: white;">Note: <?php echo htmlspecialchars($movie->vote_average); ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Popular series -->
    <section class="section">
        <h2>📺 Séries Populaires</h2>
        <div class="movies-grid">
            <?php foreach ($popularSeries as $series): ?>
                <div class="movie-card">
                    <a href="../public/Router.php?controller=series&action=details&id=<?php echo $series->id; ?>&type=tv">
                        <?php if (!empty($series->poster_path)): ?>
                            <img src="https://image.tmdb.org/t/p/w300<?php echo $series->poster_path; ?>" alt="<?php echo htmlspecialchars(getTitle($series)); ?>">
                        <?php else: ?>
                            <div class="no-image">
                                <img src="../public/no-image.jpg" alt="Photo Indisponible">
                            </div>
                        <?php endif; ?>
                        <div class="movie-info">
                            <h3 style="color: white;"><?php echo htmlspecialchars(getTitle($series)); ?></h3>
                            <p style="color: white;">Note: <?php echo htmlspecialchars($series->vote_average); ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<?php include __DIR__ . '/footer.php'; ?>
