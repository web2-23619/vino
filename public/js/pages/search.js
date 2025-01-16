import App from "../components/App.js";

(function () {
    new App();

    // Obtenir l’élément d’entrée de recherche
    const searchInput = document.querySelector("#search");

    if (searchInput) {
        fetchSuggestions();

        //inline autocomplete
        searchInput.addEventListener("input", handleAutocomplete);
    }

    /**
     * Fetch suggestions from the server
     */
    async function fetchSuggestions() {
        try {
            const response = await fetch("/recherche-autocomplete?query=");
            const data = await response.json();
            window.suggestions = data.map((item) => item.toLowerCase()); 
        } catch (error) {
            console.error("Erreur lors de la récupération des suggestions :", error);
        }
    }

    /**
     * Handle the inline autocomplete logic
     */
    function handleAutocomplete(event) {
        const inputValue = event.target.value.trim().toLowerCase();

        if (!window.suggestions || inputValue.length < 2) return;

        // Filtre pour les correspondances strictes (condition startsWith)
        const matches = window.suggestions.filter((item) =>
            item.startsWith(inputValue)
        );

        if (matches.length > 0) {
            // Utiliser la première correspondance pour le remplissage automatique
            const match = matches[0];
            event.target.value = match;
            event.target.setSelectionRange(inputValue.length, match.length);
        } else {
            // S’il n’y a pas de correspondance stricte, effacez l’entrée
            event.target.setSelectionRange(inputValue.length, inputValue.length);
        }
    }
})();
