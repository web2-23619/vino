import App from "../components/App.js";

(function () {
    new App();

    // Get the search input element
    const searchInput = document.querySelector("#search");

    if (searchInput) {
        // Fetch the autocomplete suggestions when the input is focused
        fetchSuggestions();

        // Handle the input event for inline autocomplete
        searchInput.addEventListener("input", handleAutocomplete);
    }

    /**
     * Fetch suggestions from the server
     */
    async function fetchSuggestions() {
        try {
            const response = await fetch("/recherche-autocomplete?query=");
            const data = await response.json();
            window.suggestions = data.map((item) => item); // Store suggestions globally
        } catch (error) {
            console.error("Erreur lors de la récupération des suggestions :", error);
        }
    }

    /**
     * Handle the inline autocomplete logic
     */
    function handleAutocomplete(event) {
        const inputValue = event.target.value.toLowerCase();

        if (!window.suggestions) return;

        // Find the first matching suggestion
        const match = window.suggestions.find((item) =>
            item.toLowerCase().startsWith(inputValue)
        );

        if (match && inputValue.length > 0) {
            // Autofill the input field with the matched suggestion
            event.target.value = match;
            event.target.setSelectionRange(inputValue.length, match.length);
        }
    }
})();
