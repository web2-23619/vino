<form class="form-group" action="{{ route('search.results') }}" method="POST">
    @csrf
    <div >
        <input
            type="text"
            id="search"
            name="query"
            placeholder="Entrez votre recherche"
            minlength="2" 
            value="{{ request('query') }}"
            required
            autocomplete="off"
        />
        <button type="submit" class="btn-search"></button>
    </div>
</form>
