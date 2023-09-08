(function(window) {
    
    var sharebox = {};

    /**
     * Merge defaults with user options
     * @private
     * @param {Object} defaults Default settings
     * @param {Object} options User options
     * @returns {Object} Merged values of defaults and options
     */
    sharebox.extend = function ( defaults, options ) {
        var extended = {};
        var prop;
        for (prop in defaults) {
            if (Object.prototype.hasOwnProperty.call(defaults, prop)) {
                extended[prop] = defaults[prop];
            }
        }
        for (prop in options) {
            if (Object.prototype.hasOwnProperty.call(options, prop)) {
                extended[prop] = options[prop];
            }
        }
        return extended;
    };
    sharebox.get = function(path_string, default_value) {
        var items = (path_string || '').split('.');

        var last, name, scope = window;
        for (var i=0, len=items.length; i<len; i++) {
            name = items[i];
            if (scope[name] === undefined) {
                return default_value;
            }
            last = scope;
            scope = scope[name];
        }
        return scope;
    };

    sharebox.define = function(namespace_string, value) {
        var items = (namespace_string || '').split('.');

        var last, name, scope = window;
        for (var i=0, len=items.length; i<len; i++) {
            name = items[i];
            if (typeof scope[name] != 'object') {
                scope[name] = {};
            }
            last = scope;
            scope = scope[name];
        }
        if (value) {
            last[name] = value;
        }
        return scope;
    };

    sharebox.each = function each(object, callback) {
        for (var i in object) {
            if (object.hasOwnProperty(i)) {
                if (callback(object[i], i, object) === false) {
                    return false;
                }
            }
        }
    };

    sharebox.throttle = function(func, wait) {
        wait = wait || 100;
        var timer = null;
        return function() {
            if (timer === null) {
                var args = Array.prototype.slice.call(arguments);
                timer = setTimeout(function() {
                    func.apply(func, args);
                    timer = null;
                }, wait); 
            }
        };
    };

    sharebox.debounce = function(func, wait) {
        wait = wait || 100;
        var timeout;
        return function() {
            clearTimeout(timeout);
            var args = Array.prototype.slice.call(arguments);
            timeout = setTimeout(function() {
                func.apply(func, args);
            }, wait);
        };
    };

    var api_request = function(method, endpoint, data) {
        return $.ajax({
            url: endpoint,
            type: method,
            data: data
        });
    };
    var create_api_handler = function(method) {
        return (function(method) {
            return function(endpoint, data) {
                return api_request(method, endpoint, data);
            };
        })(method);
    };
    sharebox.api = {
        request: api_request,
        get: create_api_handler('GET'),
        post: create_api_handler('POST'),
        delete: create_api_handler('DELETE'),
        put: create_api_handler('PUT'),
        option: create_api_handler('OPTION'),
        head: create_api_handler('HEAD')
    };

    window.sharebox = sharebox;

    // Bootstrap related stuff
    jQuery(function() {
        // Enable tooltips everywhere
        $('[data-toggle="tooltip"]').tooltip();
    });
})(window);

