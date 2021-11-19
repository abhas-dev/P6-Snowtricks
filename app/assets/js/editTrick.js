const axios = require("axios");

// Change div content
function showOutput(res){
    document.querySelector('#js-content').innerHTML = ``
}

let links = document.querySelectorAll('[data-delete]');
// Delete Image
function onClickDeleteBtn(e) {
    e.preventDefault();
    const url = this.href;
        if (confirm('Voulez-vous vraiment supprimer cette image?')) {
            axios.delete(this.getAttribute('href'), {
                headers: {'X-Requested-With': 'XMLHttpRequest'},
                // On recupere le token dans le dataset l'attribut token
                data: {"_token": this.dataset.token}
            })
                .then(response => response.data)
                .then(data => {data.code === 200 ? this.parentElement.remove() : alert(data.message)})
                .catch(error => {
                    console.log(error);
                })
        }
}

links.forEach(link => {
    link.addEventListener('click', onClickDeleteBtn)
});


// Set Main Picture
const radios = document.querySelectorAll('.js-mainPicture');
// const link = document.querySelectorAll('[data-mainPicture]');

async function onSelectMainPicture(e){
    // console.log(this.dataset.mainpicture);
    try{
        const response = await axios.get(this.dataset.mainpicture);
        console.log(response);
    } catch(error){
        console.log(error);
    }


}

radios.forEach(radio => radio.addEventListener('change', onSelectMainPicture));
