<div class="filters">
	<p>Filtrer</p>
	<form data-js="filtersForm" class="invisible">
		<details class="filter__criterion">
			<summary>Type</summary>
		</details>
		<div class="filter__content">
			@foreach($types as $type)
			<div class="filters__pair">
				<input type="checkbox" name="type" id="{{$type->type}}" value="{{$type->type}}">
				<label for="{{$type->type}}">{{$type->type}}</label>
			</div>
			@endforeach
		</div>
		<section>
			<details class="filter__criterion">
				<summary>Origine</summary>
			</details>
			<div class="filter__content">
				@foreach($initialCountries as $country)
				<div class="filters__pair">
					<input type="checkbox" name="country" id="{{ $country }}" value="{{ $country }}">
					<label for="{{ $country }}">{{ $country }}</label>
				</div>
				@endforeach
				<span data-js="afficherPlus">Afficher plus ({{$remainingCount}})</span>
				<div class="invisible">
					@foreach($remainingCountries as $country)
					<div class="filters__pair">
						<input type="checkbox" name="country" id="{{ $country }}" value="{{ $country }}">
						<label for="{{ $country }}">{{ $country }}</label>
					</div>
					@endforeach
				</div>
				<span data-js="afficherMoins" class="invisible">Afficher moins</span>
			</div>
		</section>
		<section>
			<details class="filter__criterion">
				<summary>Prix</summary>
			</details>
			<div class="filter__content">
				<div class="filters__pair">
					<label for="min">Minimum</label>
					<input type="number" name="min" id="min" step="0.01">
				</div>
				<div class="filters__pair">
					<label for="max">Maximum</label>
					<input type="number" name="max" id="max" step="0.01">
				</div>
			</div>

		</section>
		<button class="btn btn_outline_dark" data-js="resetFilters">Reinitialiser</button>
		<button class="btn">Appliquer</button>
	</form>
</div>