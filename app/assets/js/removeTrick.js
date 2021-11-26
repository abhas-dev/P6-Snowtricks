import axios from "axios";

const removeTrickBtn = document.querySelectorAll('#js-deleteTrick');

async function onRemoveTrick(e){
    e.preventDefault();
    try {
        const url = this.getAttribute('href');
        let slug = url.match( /trick\/(.+)\/delete/)[1];

        const response = await axios.delete(url, {
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            // On recupere le token dans le dataset l'attribut token
            data: {"_token": this.dataset.token}
        })
            .then(response => response.data)
            .then(data => {
                let trickDiv = document.getElementById("trick-" + slug);
                data.code === 200 ? trickDiv.parentNode.removeChild(trickDiv) : alert(data.message)
                // this.parentElement.parentElement.parentElement.parentElement.remove()
            })

    } catch (e) {
        console.log(e);
    }
}

removeTrickBtn.forEach(removeBtn => {
    removeBtn.addEventListener('click', onRemoveTrick)
});