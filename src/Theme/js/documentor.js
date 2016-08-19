(function ()
{
    "use strict";
    function levenshteinDistance(s, t)
    {
        let d = []; //2d matrix

        // Step 1
        let n = s.length;
        let m = t.length;

        if (n == 0) return m;
        if (m == 0) return n;

        if (s == t) return 0;
        if (s.indexOf(t) !== -1 || t.indexOf(s) !== -1) return 1;
        if (s.startsWith(t) || t.startsWith(s)) return 2;
        if (s.endsWith(t) || t.endsWith(s)) return 2;

        //Create an array of arrays in javascript (a descending loop is quicker)
        for (let i = n; i >= 0; i--) d[i] = [];

        // Step 2
        for (let i = n; i >= 0; i--) d[i][0] = i;
        for (let j = m; j >= 0; j--) d[0][j] = j;

        // Step 3
        for (let i = 1; i <= n; i++) {
            let s_i = s.charAt(i - 1);

            // Step 4
            for (let j = 1; j <= m; j++) {

                //Check the jagged ld total so far
                if (i == j && d[i][j] > 4) return n;

                let t_j  = t.charAt(j - 1);
                let cost = (s_i == t_j) ? 0 : 1; // Step 5

                //Calculate the minimum
                let mi = d[i - 1][j] + 1;
                let b  = d[i][j - 1] + 1;
                let c  = d[i - 1][j - 1] + cost;

                if (b < mi) mi = b;
                if (c < mi) mi = c;

                d[i][j] = mi; // Step 6

                //Damerau transposition
                if (i > 1 && j > 1 && s_i == t.charAt(j - 2) && s.charAt(i - 2) == t_j) {
                    d[i][j] = Math.min(d[i][j], d[i - 2][j - 2] + cost);
                }
            }
        }

        // Step 7
        return d[n][m];
    }

    function sortBestLefenshtein(a, b)
    {
        return a[0] - b[0];
    }

    let e = document.getElementById('search');
    e.addEventListener('input', function ()
    {
        let matches = [],
            length  = searchDataset.length,
            match   = 100,
            result  = document.getElementById('search-result');

        if (this.value !== '') {
            result.style.display = 'inline-block';
        } else {
            result.style.display = 'none';
            return;
        }

        for (let i = 0; i < length; i++) {
            match = levenshteinDistance(this.value.toLowerCase(), searchDataset[i][1].toLowerCase());

            if (matches.length > 9 && matches[matches.length - 1][0] > match) {
                matches[matches.length - 1] = [match, searchDataset[i], this.value];
                matches.sort(sortBestLefenshtein);
            } else if (matches.length < 10) {
                matches.push([match, searchDataset[i], this.value]);
                matches.sort(sortBestLefenshtein);
            }
        }

        result.innerHTML = '';

        length  = matches.length;
        let li  = null,
            a   = null,
            out = null;
        for (let i = 0; i < length; i++) {
            out = matches[i][1][0].split('\\');

            a = document.createElement('a');
            a.appendChild(document.createTextNode(out[out.length - 2] + '\\' + matches[i][1][1]));
            a.href = BASE + '/' + matches[i][1][0].replace('/\\/g', '/') + '.html';

            li = document.createElement('li');
            li.appendChild(a);
            result.appendChild(li);
        }
    });
}());
