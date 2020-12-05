for (let child of document.getElementsByClassName("collapsable-button")) {
    child.addEventListener("click", function () {
        this.classList.toggle("collapsable-button-active");
        this.classList.toggle("collapsable-button-inactive");
        for(let element of this.parentElement.getElementsByClassName("collapsable-content")) {
            if (element.style.maxHeight) {
                element.style.maxHeight = null;
            } else {
                element.style.maxHeight = element.scrollHeight + "px";
            }
        }
    });
}