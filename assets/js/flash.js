document.addEventListener('DOMContentLoaded', removeFlashes);

function removeFlashes() {
    let flashes = document.querySelectorAll('div.alert');
    setTimeout(function () {
        for (let flash of flashes) {
            flash.remove();
        }
    }, 8000);
}