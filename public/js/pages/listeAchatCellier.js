document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById('addBottleForm');
    const cellarButtons = document.querySelectorAll('.cellar-button');

    // Si la source est 'listeAchat', l'action par défaut du formulaire sera '/listeAchat/bouteille/ajouter'
    if (document.querySelector('input[name="source"]').value === 'listeAchat') {
        form.action = '/listeAchat/bouteille/ajouter';
    } else {
        form.action = '/cellier/bouteille/ajouter';
    }

    // Ajouter un événement pour chaque bouton des celliers
    cellarButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Mettre à jour le champ caché cellar_id avec l'id du cellier sélectionné
            const cellarId = this.getAttribute('data-cellar-id');
            document.getElementById('cellar_id').value = cellarId;

            // Soumettre le formulaire automatiquement
            form.submit();
        });
    });

    // Ajouter un événement de soumission
    form.addEventListener('submit', function () {
        if (document.querySelector('input[name="source"]').value === 'listeAchat') {
            form.action = '/listeAchat/bouteille/ajouter';
        } else {
            form.action = '/cellier/bouteille/ajouter';
        }
    });
});