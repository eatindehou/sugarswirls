let btnCheckbox = document.getElementById('ajoutSans')
let formEcheance = document.querySelector('.ajoutEcheance')


    function ajoutEcheance() {
        if (btnCheckbox.checked) {
            formEcheance.style.display = "block";
        } else {
            formEcheance.style.display = "none";
        }
    }
    
    ajoutEcheance();
    
    btnCheckbox.addEventListener('change', ajoutEcheance);
