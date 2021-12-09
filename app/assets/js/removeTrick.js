import axios from "axios";

const removeTrickBtn = document.querySelectorAll('#js-deleteTrick');
// const tricksContainer = document.querySelector('.js-trick-card');
const tricksContainer = document.querySelector('#tricks');

async function onRemoveTrick(e){
    if( e.target.id === 'js-deleteTrick' ){
        e.preventDefault();
        try {
            console.log(this)
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
}

// async function onRemoveTrickDetected(e){
//     if( e.target.id == 'js-deleteTrick' ){
//         console.log('test');
//
//     }
//     if(e.target.nodeName.toLowerCase() !== 'button') return;
//
//     const trickSlug = e.target.dataset.slug;
//     console.log(trickSlug);
//     console.log('aaaa');
//
// }

// removeTrickBtn.forEach(removeBtn => {
//     removeBtn.addEventListener('click', onRemoveTrick)
// });

// document.body.addEventListener( 'click', onRemoveTrick);


tricksContainer.addEventListener('click', onRemoveTrick);


