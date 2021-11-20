import axios from "axios";

const removeTrickBtn = document.querySelectorAll('#js-deleteTrick');
const trickCard = document.querySelectorAll('#js-trickCard');

async function onRemoveTrick(e){
    e.preventDefault();
    try {
        const response = await axios.delete(this.getAttribute('href'), {
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            // On recupere le token dans le dataset l'attribut token
            data: {"_token": this.dataset.token}
        })
            .then(response => response.data)
            .then(data => {data.code === 200 ? this.parentElement.parentElement.parentElement.parentElement.remove() : alert(data.message)})

    } catch (e) {
        console.log(e);
    }
}

removeTrickBtn.forEach(removeBtn => {
    removeBtn.addEventListener('click', onRemoveTrick)
});