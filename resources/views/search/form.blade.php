<form class="search-form" action="{{ route('search.results') }}" method="POST">
    @csrf
    <div class="form-group">
        <input
            type="text"
            id="search"
            name="query"
            placeholder="Entrez votre recherche"
            value="{{ request('query') }}"
            required
            autocomplete="off"
        />
        <button type="submit" class="btn-search"></button>
    </div>
</form>
