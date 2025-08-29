let selectedRating = 0;
let ratingConfirmed = false;
const stars = document.querySelectorAll(".star-popup");
const confirmationExternalMessage = document.getElementById("confirmation-external-message");
const immediateRatingText = document.getElementById("immediate-rating-text");
const cancelRatingButton = document.getElementById("cancel-rating-button");
const externalStar = document.getElementById("open-rating-popup");

document.addEventListener("DOMContentLoaded", function () {
    // Open the rating popup when the user clicks on the rating star
    // Highlight stars based on mouse hover
    stars.forEach(star => {
        star.addEventListener("mouseover", function () {
            highlightStars(this.getAttribute("data-value"));
        });
        star.addEventListener("mouseout", function () {
            highlightStars(selectedRating);
        });
        star.addEventListener("click", function () {
            selectedRating = this.getAttribute("data-value");
            highlightStars(selectedRating);
            immediateRatingText.textContent = selectedRating; // Display the rating immediately
            immediateRatingText.style.display = "block"; // Ensure it is displayed
        });
    });
    initRating();
});


// 
// Function to highlight the stars based on the rating value
function highlightStars(value) {
    stars.forEach(star => {
        star.style.color = parseInt(star.getAttribute("data-value")) <= parseInt(value) ? "#FFD700" : "#ccc";
    });
}

// Reset the star colors after canceling the rating
function resetStars() {
    stars.forEach(star => {
        star.style.color = "#ccc";
    });
}

// function to display the rating popup
function openRating() {
    document.getElementById("rating-popup").style.display = "block";
}

function closeRating() {
    document.getElementById("rating-popup").style.display = "none";
}


// function to display a rating
function displayRating(rating) {
    popup = document.getElementById("rating-popup");
    popup.style.display = "none";
    confirmationExternalMessage.textContent = "Your rating is  " + rating;
    confirmationExternalMessage.style.display = "block";
    cancelRatingButton.style.display = "inline-block";
    externalStar.style.color = "#FFD700";
    ratingConfirmed = true;

    immediateRatingText.textContent = rating;
    immediateRatingText.style.display = "block";
}

// function to add a rating in the database
function addRating() {
    if (selectedRating > 0) {
        displayRating(selectedRating)
        fetch(`../public/Router.php?controller=movie&action=insertUserRating&idMovieExternal=${movieId}&rating=${selectedRating}`)
            .then(response => response.json())
            .catch(error => console.error("Erreur lors du chargement de la note:", error));
    }
}

// cancel the rating
function resetRating() {
    if (ratingConfirmed) {
        selectedRating = 0;
        resetStars();
        confirmationExternalMessage.textContent = "Your rating: -";
        immediateRatingText.textContent = "-";
        immediateRatingText.style.display = "none";
        confirmationExternalMessage.style.display = "none";
        cancelRatingButton.style.display = "none";
        externalStar.style.color = "#ccc";
        ratingConfirmed = false;
        popup.style.display = "none";

        fetch(`../public/Router.php?controller=movie&action=deleteUserRating&idMovieExternal=${movieId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log("Note supprimée avec succès.");
                } else {
                    console.error("Erreur lors de la suppression de la note:", data.message);
                }
            })
            .catch(error => console.error("Erreur lors de la requête de suppression:", error));
    }
}


// get and display the rating from the db
function initRating() {
    const movieIdElement = document.getElementById("movie-id");
    if (!movieIdElement) return;

    fetch(`../public/Router.php?controller=movie&action=getUserRating&idMovieExternal=${movieId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.rating !== null) {
                selectedRating = data.rating;
                highlightStars(selectedRating);
                displayRating(selectedRating);
            }
        })
        .catch(error => console.error("Erreur lors du chargement de la note:", error));
}