<form class="search">
	@csrf
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
		<button class="btn_scanner no-bg" data-js-action="scanner"> Scanner bouteille
				<svg version="1.0" xmlns="http://www.w3.org/2000/svg"
				width="35px" height="20px" viewBox="0 0 300.000000 300.000000"
				preserveAspectRatio="xMidYMid meet">
				<metadata>
				Created by potrace 1.10, written by Peter Selinger 2001-2011
				</metadata>
				<g transform="translate(0.000000,300.000000) scale(0.100000,-0.100000)"
				fill="#000000" stroke="none">
				<path d="M92 2323 l3 -258 48 -3 47 -3 0 210 0 211 210 0 210 0 0 50 0 50
				-260 0 -260 0 2 -257z"/>
				<path d="M2390 2530 l0 -50 210 0 210 0 0 -211 0 -210 48 3 47 3 3 258 2 257
				-260 0 -260 0 0 -50z"/>
				<path d="M377 2293 c-4 -3 -7 -141 -7 -305 l0 -298 95 0 95 0 0 305 0 305 -88
				0 c-49 0 -92 -3 -95 -7z"/>
				<path d="M707 2293 c-4 -3 -7 -141 -7 -305 l0 -298 70 0 70 0 0 305 0 305 -63
				0 c-35 0 -67 -3 -70 -7z"/>
				<path d="M940 1995 l0 -305 45 0 45 0 0 305 0 305 -45 0 -45 0 0 -305z"/>
				<path d="M1127 2293 c-4 -3 -7 -141 -7 -305 l0 -298 50 0 50 0 0 305 0 305
				-43 0 c-24 0 -47 -3 -50 -7z"/>
				<path d="M1360 1995 l0 -305 95 0 95 0 -2 303 -3 302 -92 3 -93 3 0 -306z"/>
				<path d="M1690 1995 l0 -305 70 0 70 0 0 305 0 305 -70 0 -70 0 0 -305z"/>
				<path d="M1970 1995 l0 -305 45 0 45 0 0 305 0 305 -45 0 -45 0 0 -305z"/>
				<path d="M2160 1995 l0 -305 45 0 45 0 0 305 0 305 -45 0 -45 0 0 -305z"/>
				<path d="M2440 1995 l0 -305 95 0 95 0 -2 303 -3 302 -92 3 -93 3 0 -306z"/>
				<path d="M140 1500 l0 -90 1360 0 1360 0 0 90 0 90 -1360 0 -1360 0 0 -90z"/>
				<path d="M372 1008 l3 -303 93 -3 92 -3 0 306 0 305 -95 0 -95 0 2 -302z"/>
				<path d="M702 1008 l3 -303 68 -3 67 -3 0 306 0 305 -70 0 -70 0 2 -302z"/>
				<path d="M940 1005 l0 -305 45 0 45 0 0 305 0 305 -45 0 -45 0 0 -305z"/>
				<path d="M1122 1008 l3 -303 48 -3 47 -3 0 305 0 306 -50 0 -50 0 2 -302z"/>
				<path d="M1360 1005 l0 -306 93 3 92 3 3 303 2 302 -95 0 -95 0 0 -305z"/>
				<path d="M1690 1005 l0 -305 70 0 70 0 0 305 0 305 -70 0 -70 0 0 -305z"/>
				<path d="M1970 1005 l0 -305 45 0 45 0 0 305 0 305 -45 0 -45 0 0 -305z"/>
				<path d="M2160 1005 l0 -305 45 0 45 0 0 305 0 305 -45 0 -45 0 0 -305z"/>
				<path d="M2440 1005 l0 -306 93 3 92 3 3 303 2 302 -95 0 -95 0 0 -305z"/>
				<path d="M97 933 c-4 -3 -7 -120 -7 -260 l0 -253 260 0 260 0 0 50 0 50 -210
				0 -210 0 0 210 0 210 -43 0 c-24 0 -47 -3 -50 -7z"/>
				<path d="M2810 731 l0 -211 -210 0 -210 0 0 -50 0 -50 260 0 260 0 -2 258 -3
				257 -47 3 -48 3 0 -210z"/>
				</g>
				</svg>
		</button>
</form>