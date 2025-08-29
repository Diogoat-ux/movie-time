import { displayList } from './lists.js';

export function fetchLists() {
    var xhr = new XMLHttpRequest();

    // Set up a function to handle the response when the request state changes
    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var lists = JSON.parse(xhr.responseText);

            // Check if the parsed response is an array
            if (Array.isArray(lists)) {
                // Loop through each list and call the displayList function with the list's name and ID
                lists.forEach((e) => displayList(e.NAME_LIST, e.ID_LISTS));
            } else {
                console.error('The response is not a valid list');
            }
        }
    };

    xhr.open('GET', '../public/Router.php?controller=movie&action=displayLists', true);

    xhr.send();
}
