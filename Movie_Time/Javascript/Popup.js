const popupConteneur = document.createElement('div');
popupConteneur.classList.add("popup_conteneur");
document.body.appendChild(popupConteneur);

function openPopup(titre, message, time = 0) {
    let popup = document.createElement('div');
    popup.classList.add("MessagePopup");
    popupConteneur.appendChild(popup);

    let popup_titre = document.createElement('h3');
    popup_titre.innerText = titre;
    popup.appendChild(popup_titre);

    let popup_message = document.createElement('p');
    popup_message.innerText = message;
    popup.appendChild(popup_message);
    popup.classList.add("active");

    popup.addEventListener('click', () => {
        closePopup(popup);
    });
    if (time != 0) {
        setTimeout(() => {
            closePopup(popup)
        }, time);
    }
}

function closePopup(popup) {
    popup.classList.remove("active");
    popup.classList.add("closing");
    setTimeout(() => {
        popupConteneur.removeChild(popup);
    }, 200);
}

