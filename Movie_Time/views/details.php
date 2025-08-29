<?php
include 'header.php';
require_once '../controllers/MovieController.php';
$controller = new MovieController();
?>

<div class="details-container">
    <?php if (isset($details)): ?>
        <div class="details-poster">
            <?php if (!empty($details->poster_path)): ?>
                <img src="https://image.tmdb.org/t/p/w300<?php echo $details->poster_path; ?>"
                    alt="<?php echo htmlspecialchars($details->title ?? $details->name ?? 'Titre inconnu'); ?>">
            <?php else: ?>
                <div class="no-image" style="text-align: center; background-color: #222; padding: 10px;">
                    <img src="../public/no-image.jpg" alt="Photo Indisponible" style="width: 150px; height: auto;">
                    <p style="color: white; margin-top: 10px;">📌 Photo Indisponible</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Affichage unique du titre et de la description -->
        <h2><?php echo htmlspecialchars($details->title ?? $details->name ?? 'Titre inconnu'); ?></h2>
        <p>
            <?php
            if (!empty($details->overview)) {
                echo htmlspecialchars($details->overview);
            } else {
                echo "<span style='color: gray; font-style: italic;'>📜 Descdeewdewription non disponible.</span>";
            }

            ?>
        </p>
        <p>Rating: <?php echo htmlspecialchars($details->vote_average); ?></p>

        <!-- Zone de notation -->
        <p style="display: inline; font-weight: bold;">Rate:</p>
        <div class="single-star" id="open-rating-popup"
            style="display: inline; font-size: 30px; cursor: pointer; color: #ccc;"
            onclick="checkAndOpenRating()">&#9733;</div>
        <div style="margin-top: 20px;">
            <p id="confirmation-external-message" style="display: none; font-size: 18px; color: white; margin-top: 10px;">
                Your rating is confirmed: -
            </p>
        </div>

        <!-- Boutons pour gérer les listes -->
        <div class="list-buttons">
            <button id="btnList">Créer une liste</button>
            <button id="btnAddList">Ajouter à une liste existante</button>
        </div>

    <?php else: ?>
        <p>Details not available.</p>
    <?php endif; ?>
</div>

<div id="movie-id" data-id="<?= htmlspecialchars($details->id) ?>"></div>

<!-- Popup pour la notation -->
<div id="rating-popup" class="popup">
    <button id="close-rating-popup" onclick="closeRating()">&#10005;</button>
    <h3>Give your rating</h3>
    <div class="star-rating-popup">
        <?php for ($i = 1; $i <= 10; $i++): ?>
            <span class="star-popup" data-value="<?php echo $i; ?>" style="color: #ccc; font-size: 25px; cursor: pointer;">&#9733;</span>
        <?php endfor; ?>
    </div>
    <p id="popup-rating-text">Your rating: -</p>
    <p id="immediate-rating-text">-</p>
    <button id="confirm-rating-button" onclick="addRating()">Confirm my rating</button>
    <button id="cancel-rating-button" onclick="resetRating()">Cancel my rating</button>
</div>

<script>
    // Variable indicating whether user is logged in (based on PHP session)
    const isLoggedIn = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;
    const movieId = <?= isset($details->id) ? json_encode($details->id) : 'null' ?>;

    // Managing list and notation buttons
    document.addEventListener("DOMContentLoaded", function() {

        // Create a list” button
        const btnList = document.getElementById("btnList");
        if (btnList) {
            btnList.addEventListener("click", function() {
                if (!isLoggedIn) {
                    openPopup("Erreur", "Veuillez vous connecter pour créer une liste.", 3000);
                    return;
                }
                openInputPopUp().then((listName) => {
                    if (listName === null || listName.trim() === "") {
                        openPopup('Annulation', "Action annulée.", 3000);
                        return;
                    }
                    const xhr = new XMLHttpRequest();
                    xhr.open("GET", `../public/Router.php?controller=movie&action=createListWithMovie&idMovie=${encodeURIComponent(movieId)}&name=${encodeURIComponent(listName)}`, true);
                    xhr.onload = function() {
                        console.log(xhr.responseText);
                        if (xhr.status === 200) {
                            openPopup('Succès', "La liste a été créée avec succès", 3000);
                        } else {
                            openPopup('Erreur', "Erreur, la liste n'a pas été créée.", 3000);
                        }
                    };
                    xhr.onerror = function() {
                        openPopup('Erreur', "Network error. Please try again.", 3000);
                    };
                    xhr.send();
                });
            });
        }

        // "Add to an existing list" button
        const btnAddList = document.getElementById("btnAddList");
        if (btnAddList) {
            btnAddList.addEventListener("click", function() {
                if (!isLoggedIn) { // Check if the user is logged in
                    openPopup("Erreur", "Veuillez vous connecter pour ajouter à une liste existante.", 3000);
                    return;
                }
                if (!movieId) { // Check if the movie ID is available
                    openPopup('Erreur', "ID du film introuvable.", 3000);
                    return;
                }
                var divListContainer = document.querySelector(".ListContainer"); // Try to find an existing element with the class "ListContainer"
                if (!divListContainer) { // If it doesn't exist, create a new div element
                    divListContainer = document.createElement("div");
                    divListContainer.classList.add("ListContainer");
                    document.body.appendChild(divListContainer);
                } else { // If the element already exists, clear its content
                    divListContainer.innerHTML = "";
                }
                const btnClose = document.createElement("button");
                btnClose.innerText = "X";
                btnClose.addEventListener("click", function() { // Add a click event to remove divListContainer when the button is clicked
                    divListContainer.remove();
                });
                divListContainer.appendChild(btnClose); // Add the close button to divListContainer
                const title = document.createElement("h2");
                title.innerText = "Sélectionnez une liste";
                title.classList.add("list-title");
                divListContainer.appendChild(title); // Add the title to divListContainer
                const xhr = new XMLHttpRequest(); // Create a new XMLHttpRequest to fetch movie lists
                xhr.open("GET", `../public/Router.php?controller=movie&action=displayLists`, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const lists = JSON.parse(xhr.responseText);
                        if (lists.length === 0) { // If there are no lists, display a message and stop execution
                            divListContainer.innerHTML += "<p>Aucune liste disponible. Créez-en une.</p>";
                            return;
                        }
                        lists.forEach(list => { // Loop through each list and create a button for it
                            const listButton = document.createElement("button");
                            listButton.innerText = list.NAME_LIST;
                            listButton.addEventListener("click", function() { // Add a click event to add the movie to the selected list
                                addMovieToList(movieId, list.ID_LISTS);
                            });
                            divListContainer.appendChild(listButton); // Add the button to the list container
                        });
                    }
                };
                xhr.send();
            });
        }
    });

    // For the rating button, we check the connection before opening the popup.
    function checkAndOpenRating() {
        if (!isLoggedIn) {
            openPopup("Erreur", "Veuillez vous connecter pour noter ce film.", 3000);
            return;
        }
        openRating();
    }

    function addMovieToList(movieId, listId) { // This function adds a movie to a list
        const xhr = new XMLHttpRequest();
        xhr.open("GET", `../public/Router.php?controller=movie&action=addMovieToList&idMovie=${encodeURIComponent(movieId)}&idList=${encodeURIComponent(listId)}`, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    openPopup('Succès', "Film ajouté à la liste avec succès !", 3000);
                    document.querySelector(".ListContainer").remove();
                } else {
                    openPopup('Erreur', "Erreur lors de l'ajout du film.", 3000);
                }
            }
        };
        xhr.send();
    }
</script>
<script src="../Javascript/Rating.js"></script>
<?php include 'footer.php'; ?>