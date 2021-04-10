/*
    Created by Dmitry Grebenin
    2021
*/

function parseCollapsable() {
    for (let child of document.getElementsByClassName("collapsable-button")) {
        child.addEventListener("click", function () {
            this.classList.toggle("collapsable-button-active");
            this.classList.toggle("collapsable-button-inactive");
            for (let element of this.parentElement.getElementsByClassName("collapsable-content")) {
                if (element.style.maxHeight) {
                    element.style.maxHeight = null;
                } else {
                    element.style.maxHeight = element.scrollHeight + "px";
                }
            }
        });
    }
}

function validatePassword(){
    if(document.getElementById('password').value !== document.getElementById('password-confirm').value) {
        document.getElementById('password-confirm').setCustomValidity("Passwords Don't Match");
    } else {
        document.getElementById('password-confirm').setCustomValidity('');
    }
}

function addPlaylist() {
    document.getElementById('playlist-name-field').value = prompt('Please provide a name for the playlist: ');
    if (document.getElementById('playlist-name-field').value !== '') {
        document.getElementById('playlist-create-submit').click();
    }
}