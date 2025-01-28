<form class="search" action="{{ route('search.results') }}" method="POST">
	@csrf
	<div class="form-group">
		<label for="search">Explorez par nom, type de vin ou origine</label>
		<div class="search-container">
			<input
				aria-label="champ de recherche"
				type="text"
				id="search"
				name="query"
				placeholder="Entrez votre recherche"
				minlength="2"
				value="{{ request('query') }}"
				required
				autocomplete="off" />
			<button type="submit" class="btn btn_compact">
				Chercher
			</button>
			<ul class="search_suggestions" style="display: none;"></ul>
		</div>
	</div>
</form>