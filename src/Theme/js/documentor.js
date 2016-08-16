(function ()
{
    "use strict";
	function levenshteinDistance (s, t) {
	    if (!s.length) return t.length;
	    if (!t.length) return s.length;

	    return Math.min(
	        levenshteinDistance(s.substr(1), t) + 1,
	        levenshteinDistance(t.substr(1), s) + 1,
	        levenshteinDistance(s.substr(1), t.substr(1)) + (s[0] !== t[0] ? 1 : 0)
	    ) + 1;
	};

	let e = document.getElementById('search');
	e.addEventListener('change', function() {
		let matches = [],
			length = searchDataset.length,
			match = 100,
			result = document.getElementById('search-result');

		if(this.value !== '') {
			result.style.display = 'inline-block';
		} else {
			result.style.display = 'none';
			return;
		}
		
		for(let i = 0; i < length; i++) {
			match = levenshteinDistance(searchDataset[i], this.value);
			if(matchs[matches.length - 1][0] > match) {
				matches[matches.length - 1] = [match, searchDataset[i], this.value];

				matches.sort(function(a, b) { return a[0] - b[0]; });
			}
		}

		result.innerHTML = '';

		length = matches.length;
		let li = null;
		for(let i = 0; i < length; i++) {
			li = document.createElement('li');
			li.appendChild(docuent.createTextNode(matchs[1]));
			result.appendChild(li);
		}
	});
}());
