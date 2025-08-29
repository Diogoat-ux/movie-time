function openConfirmationPopup(titre, message, onConfirm) {
    let popup = document.createElement('div');
    popup.classList.add("MessagePopup");
    popupConteneur.appendChild(popup);

    let popup_titre = document.createElement('h3');
    popup_titre.innerText = titre;
    popup.appendChild(popup_titre);

    let popup_message = document.createElement('p');
    popup_message.innerText = message;
    popup.appendChild(popup_message);

    let btnContainer = document.createElement('div');
    btnContainer.classList.add('popup-buttons');

    let confirmBtn = document.createElement('button');
    confirmBtn.innerText = "Confirmer";
    confirmBtn.classList.add('confirm-btn');
    confirmBtn.addEventListener('click', () => {
        closePopup(popup);
        onConfirm(); // Exécuter l'action si confirmé
    });

    let cancelBtn = document.createElement('button');
    cancelBtn.innerText = "Annuler";
    cancelBtn.classList.add('cancel-btn');
    cancelBtn.addEventListener('click', () => {
        closePopup(popup);
    });

    btnContainer.appendChild(confirmBtn);
    btnContainer.appendChild(cancelBtn);
    popup.appendChild(btnContainer);

    popup.classList.add("active");
}
