import App from "../components/App.js";

(function () {
    new App();

    // Obtenir l’élément d’entrée de recherche
    const searchInput = document.querySelector("#search");

    let userEditing = false; //Déclarer userEditing 

    if (searchInput) {
        fetchSuggestions();

        // Vérifier si l’utilisateur supprime du texte
        searchInput.addEventListener("keydown", (e) => {
            if (e.key === "Backspace" || e.key === "Delete") {
                userEditing = true;
            }
        });

        searchInput.addEventListener("keyup", (e) => {
            if (e.key !== "Backspace" && e.key !== "Delete") {
                userEditing = false;
            }
        });

        //autocomplete logic
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

        if (!window.suggestions || inputValue.length < 2 || userEditing) {
            return;
        }

        // Suggestions de filtres pour les correspondances strictes
        const matches = window.suggestions.filter((item) =>
            item.startsWith(inputValue)
        );

        if (matches.length > 0) {
            const match = matches[0];
            event.target.value = match;
            event.target.setSelectionRange(inputValue.length, match.length);
        }
    }
})();
