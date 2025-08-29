function openInputPopUp() {
    return new Promise((resolve) => {
        let popup = document.createElement('div');
        popup.classList.add("MessagePopup");
        popupConteneur.appendChild(popup);

        let popup_titre = document.createElement('h3');
        popup_titre.innerText = "Nouvelle liste";
        popup.appendChild(popup_titre);

        let popup_message = document.createElement('p');
        popup_message.innerText = "Entrez un nom pour la liste :";
        popup.appendChild(popup_message);

        let inputField = document.createElement('input');
        inputField.type = "text";
        inputField.classList.add('popup-input');
        inputField.placeholder = "Nom de la liste";
        popup.appendChild(inputField);

        let btnContainer = document.createElement('div');
        btnContainer.classList.add('popup-buttons');

        let confirmBtn = document.createElement('button');
        confirmBtn.innerText = "Confirmer";
        confirmBtn.classList.add('confirm-btn');
        confirmBtn.disabled = true; // Désactivé tant qu'il n'y a pas de texte

        let cancelBtn = document.createElement('button');
        cancelBtn.innerText = "Annuler";
        cancelBtn.classList.add('cancel-btn');

        // Fonction pour fermer la popup proprement
        function closeAndResolve(value) {
            closePopup(popup);
            resolve(value);
            inputField.removeEventListener("input", handleInput);
            inputField.removeEventListener("keydown", handleKeyDown);
        }

        // Vérifie si l'utilisateur a écrit quelque chose
        function handleInput() {
            confirmBtn.disabled = inputField.value.trim() === "";
        }

        // Gère les touches clavier (Enter et Escape)
        function handleKeyDown(event) {
            if (event.key === "Enter" && inputField.value.trim() !== "") {
                closeAndResolve(inputField.value.trim());
            } else if (event.key === "Escape") {
                closeAndResolve(null);
            }
        }

        confirmBtn.addEventListener('click', () => closeAndResolve(inputField.value.trim()));
        cancelBtn.addEventListener('click', () => closeAndResolve(null));

        inputField.addEventListener("input", handleInput);
        inputField.addEventListener("keydown", handleKeyDown);

        btnContainer.appendChild(confirmBtn);
        btnContainer.appendChild(cancelBtn);
        popup.appendChild(btnContainer);

        popup.classList.add("active");
        inputField.focus();
    });
}
