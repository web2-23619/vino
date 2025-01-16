
// Récupérer le formulaire et le sélecteur du cellier
const form = document.getElementById('addBottleForm');
const cellarSelect = document.getElementById('cellar_id');

// Ajouter un événement change pour modifier l'action du formulaire
cellarSelect.addEventListener('change', function () {
    // Si "Liste d'achat" est sélectionné
    if (this.value === 'wishlist') {
        // Modifier l'action du formulaire pour ajouter à la liste d'achat
        form.action = '/listeAchat/bouteille/ajouter';
    } else {
        // Restaurer l'action par défaut pour ajouter au cellier
        form.action = '/cellier/bouteille/ajouter';
    }
});
