// This function creates a new anchor element and uses location
// properties (inherent) to get the desired URL data. Some String
// operations are used (to normalize results across browsers).
/*
 * var myURL = parseURL('http://abc.com:8080/dir/index.html?id=255&m=hello#top');
 *
 * myURL.file;     // = 'index.html'
 * myURL.hash;     // = 'top'
 * myURL.host;     // = 'abc.com'
 * myURL.query;    // = '?id=255&m=hello'
 * myURL.params;   // = Object = { id: 255, m: hello }
 * myURL.path;     // = '/dir/index.html'
 * myURL.segments; // = Array = ['dir', 'index.html']
 * myURL.port;     // = '8080'
 * myURL.protocol; // = 'http'
 * myURL.source;   // = 'http://abc.com:8080/dir/index.html?id=255&m=hello#top'
 *
 */
function parseURL(url) {
    var a =  document.createElement('a');
    a.href = url;
    return {
        source: url,
        protocol: a.protocol.replace(':',''),
        host: a.hostname,
        port: a.port,
        query: a.search,
        params: (function(){
            var ret = {},
                seg = a.search.replace(/^\?/,'').split('&'),
                len = seg.length, i = 0, s;
            for (;i<len;i++) {
                if (!seg[i]) {continue;}
                s = seg[i].split('=');
                ret[s[0]] = s[1];
            }
            return ret;
        })(),
        file: (a.pathname.match(/\/([^\/?#]+)$/i) || [,''])[1],
        hash: a.hash.replace('#',''),
        path: a.pathname.replace(/^([^\/])/,'/$1'),
        relative: (a.href.match(/tps?:\/\/[^\/]+(.+)/) || [,''])[1],
        segments: a.pathname.replace(/^\//,'').split('/')
    };
}


function appendParam(url, key, val) {
    var param = key + '=' + val;
    var parts = parseURL(url);
    var oldval = eval('parts.params.' + key);
    if (oldval != undefined) {
        return parts.source.replace(key + '=' + oldval, param);
    } else {
        var hash = parts.hash != undefined ? parts.hash : '';
        if (parts.query != '') {
            return parts.source.replace('#' + hash, '') + '&' + param;
        }
        else {
            return parts.source.replace('#' + hash, '') + '?' + param;
        }
    }
}

// For param q:
// http://something/else                        => Return unmodified
// http://something/else?q=123&x=456            => Remove param and trailing ampersand
// http://something/else?q=123                  => Remove param and leading question mark
// http://something/else?x=123&q=456&y=789      => Remove param and trailing ampersand
// http://something/else?x=123&q=456            => Remove param and leading ampersand

function removeParam(url, key) {
    var parts = parseURL(url);
    var curval = eval('parts.params.' + key);
    if (curval == undefined) {
        return parts.source;
    } else {
        var curparam = key + '=' + curval;
        if (parts.source.indexOf('?' + curparam + '&') > 0)
            return parts.source.replace(curparam + '&', '');
        else if (parts.source.indexOf('?' + curparam) > 0)
            return parts.source.replace('?' + curparam, '');
        else if (parts.source.indexOf('&' + curparam + '&') > 0)
            return parts.source.replace(curparam + '&', '');
        else if (parts.source.indexOf('&' + curparam) > 0)
            return parts.source.replace('&' + curparam, '');
        else
            return parts.source;
    }
}

