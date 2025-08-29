export function displayList(listName, idList) {
    var link = document.createElement('a');
    link.classList.add('list-item');
    link.href = "#";
    link.textContent = listName;
    link.style.color = "white";
    link.style.marginRight = "15px"; 

    // Add an event listener for when the link is clicked
    link.addEventListener("click", function (event) {
        event.preventDefault();
        fetchMovies(idList, listName);
    });

    var container = document.getElementById('lists-container');
    container.appendChild(link);
}

function fetchMovies(idList, listName) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", `../public/Router.php?controller=movie&action=getMovies&list=${encodeURIComponent(idList)}`, true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            const data = JSON.parse(xhr.responseText);
            displayMovies(data, listName, idList);
        } else {
            alert("Erreur reçevant les films");
        }
    };
    xhr.send();
}

function displayMovies(movies, listName, idList) {
    const moviesContainer = document.getElementById("movies-container");
    moviesContainer.innerHTML = ""; // Clear the container before adding new content

    const titleElement = document.createElement("h2");
    titleElement.textContent = `Films de la liste ${listName}`;
    moviesContainer.appendChild(titleElement);

    const moviesListContainer = document.createElement("div");
    moviesListContainer.classList.add("movies-list");

    movies.forEach(movie => {
        const movieCard = createMovieCard(movie, idList);
        moviesListContainer.appendChild(movieCard);
    });

    moviesContainer.appendChild(moviesListContainer);

    addDeleteListLink(idList);
}

function createMovieCard(movie, idList) {
    const movieCard = document.createElement("div");
    movieCard.classList.add("movie-card");

    const movieTitle = document.createElement("p");
    movieTitle.classList.add("movie-title");
    movieTitle.textContent = movie.title;

    const movieImage = document.createElement("img");
    movieImage.src = movie.poster_path ? `https://image.tmdb.org/t/p/w300${movie.poster_path}` : "../public/no-image.jpg";
    movieImage.alt = movie.title;

    const deleteLink = document.createElement("a");
    deleteLink.href = "#";
    deleteLink.textContent = "Supprimer";
    deleteLink.style.color = "#e74c3c";  // Red color for the delete link
    deleteLink.classList.add("delete-movie");

    deleteLink.addEventListener("click", function (event) {
        event.preventDefault();
        event.stopPropagation();
        openConfirmationPopup("Confirmation", "Êtes-vous sûr de vouloir supprimer ce film de la liste?", () => {
            deleteMovie(movie, idList, movieCard);
        });
    });

    movieCard.appendChild(movieTitle);
    movieCard.appendChild(movieImage);
    movieCard.appendChild(deleteLink);

    movieCard.addEventListener("click", function () {
        window.location.href = `../public/Router.php?controller=movie&action=details&id=${encodeURIComponent(movie.id)}&type=movie`;
    });

    return movieCard;
}

// Function to delete a movie from the list
function deleteMovie(movie, idList, movieCard) {
    const xhrDel = new XMLHttpRequest();
    xhrDel.open("GET", `../public/Router.php?controller=movie&action=deleteMovie&movieid=${encodeURIComponent(movie.list_movie_id)}&listid=${encodeURIComponent(idList)}`, true);
    xhrDel.send();
    openPopup('Succès', "Film supprimée de la liste", 3000);
    movieCard.remove();
}

// Function to add the "Delete List" link
function addDeleteListLink(idList) {
    var del = document.createElement('a');
    del.classList.add('delete-list-item');
    del.href = "#";
    del.textContent = "Supprimer la liste";
    del.style.color = "#e74c3c"; // Red color for the delete link
    del.style.fontWeight = "bold";
    del.style.fontSize = "18px";
    del.style.textAlign = "center";
    del.style.display = "block"; // Ensure the button is below the movie list
    del.style.marginTop = "30px";

    del.addEventListener("click", function () {
        openConfirmationPopup("Confirmation", "Êtes-vous sûr de vouloir supprimer cette liste?", () => {
            deleteList(idList);
        });
    });

    document.getElementById("movies-container").appendChild(del);
}

// Function to delete a list
function deleteList(idList) {
    const xhrDel = new XMLHttpRequest();
    xhrDel.open("GET", `../public/Router.php?controller=movie&action=deleteList&list=${encodeURIComponent(idList)}`, true);
    xhrDel.send();
    openPopup('Success', "Liste supprimée", 3000);
    location.reload();
}
