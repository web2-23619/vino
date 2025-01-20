<form class="form-group" action="{{ route('search.results') }}" method="POST">
	@csrf
	<div class="search-container">
		<input
			type="text"
			id="search"
			name="query"
			placeholder="Entrez votre recherche"
			minlength="2"
			value="{{ request('query') }}"
			required
			autocomplete="off" />
		<button type="submit">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="black" width="16" height="16">
				<path d="M10 2a8 8 0 015.61 13.61l4.2 4.2a1 1 0 01-1.42 1.42l-4.2-4.2A8 8 0 1110 2zm0 2a6 6 0 100 12 6 6 0 000-12z" />
			</svg>
		</button>
	</div>
</form>
