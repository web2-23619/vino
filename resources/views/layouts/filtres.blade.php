<div>
	<h3>Filtres</h3>
	<form action="">
		<section>
			<h4>Type</h4>
			@foreach($types as $type)
			<input type="checkbox" name="type" id="{{$type->type}}">
			<label for="{{$type->type}}}">{{$type->type}}</label>
			@endforeach
		</section>
		<section>
			<h4>Origine</h4>
			@foreach($countries as $country)
			<input type="checkbox" name="country" id="{{$country->country}}">
			<label for="{{$country->country}}}">{{$country->country}}</label>
			@endforeach
		</section>
		<section>
			<h4>Prix</h4>
			<label for="min">Minimum</label>
			<input type="number" name="min" id="min">
			<label for="max">Maximum</label>
			<input type="number" name="max" id="max">
		</section>
		<button class="btn">Appliquer</button>
		<button class="btn btn_outline_dark">Reinitialiser</button>
	</form>

</div>