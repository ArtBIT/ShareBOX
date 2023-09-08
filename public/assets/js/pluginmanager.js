/*************************************************************************************/
/* @copyright Original work Copyright (c) 2014 Djordje Ungar                         */
/*                                                                                   */
/*     Permission is hereby granted, free of charge, to any person obtaining a copy  */
/*     of this software and associated documentation files (the "Software"), to deal */
/*     in the Software without restriction, including without limitation the rights  */
/*     to use, copy, modify, merge, publish, distribute, sublicense, and/or sell     */
/*     copies of the Software, and to permit persons to whom the Software is         */
/*     furnished to do so, subject to the following conditions:                      */
/*                                                                                   */
/*     The above copyright notice and this permission notice shall be included in    */
/*     all copies or substantial portions of the Software.                           */
/*                                                                                   */
/* @author Djordje Ungar (djordje@ungar.rs)                                          */
/* @source https://github.com/ArtBIT/PluginManager.js                                */
/*************************************************************************************/
;PluginManager = (function(window) {
    /**
     * PluginManager is a container for plugins. It has a simple
     * built-in pub-sub functionality that allows the plugins to
     * communicate with one another.
     */
    function PluginManager() {
        this.plugins = [];
        this.listeners = {};
        this.history = {};
    }
    PluginManager.prototype = {
        /**
         * Register the plugin with the manager.
         */
        add: function (plugin) {
            if (!plugin || typeof plugin["addedToPluginManager"] !== "function") {
                throw new Error("Plugin must have method addedToPluginManager(pluginManager) {}");
            }
            this.plugins[name] = plugin;
            plugin.addedToPluginManager(this);
            return this;
        },
        /**
         * Remove the specified plugin from the manager.
         */
        remove: function (plugin) {
            var i = this.plugins.length;
            while (i--) {
                if (plugin === this.plugins[i]) {
                    this.plugins.splice(i, 1);
                    break;
                }
            }
            return this;
        }, 
        /**
         * Bind a callback to an event
         */
        on: function (eventType, callback) {
            if (!(eventType && callback && typeof callback === 'function')) {
                return;
            }
            this.listeners[eventType] = this.listeners[eventType] || [];
            this.listeners[eventType].push(callback);
            return this;
        },
        /**
         * Unbind the callback from the event
         */
        off: function (eventType, callback) {
            var targets = this.listeners[eventType] || [];
            var i = targets.length;
            while (i--) {
                if (targets[i] === callback) {
                    targets.splice(i, 1);
                }
            }
            return this;
        },
        /** 
         * Trigger a specific event
         */
        trigger: function (eventType) {
            var args = Array.prototype.slice.call(arguments, 1),
                targets = this.listeners[eventType] || [],
                i = 0;

            this.record(eventType, args);
            for (i = 0; i < targets.length; i++) {
                if (typeof targets[i] === 'function') {
                    targets[i].apply(targets[i], args);
                }
            }
            return this;
        },
        /**
         * Record the event to the history so that it can be played back
         * to the late bound plugins
         */
        record: function (eventType, args) {
            if (!this.history.hasOwnProperty(eventType)) {
                this.history[eventType] = [];
            }
            this.history[eventType].push(args);
        },
        /**
         * Replay the history to this particular callback
         */
        replay: function (eventType, callback) {
            var i, len, args;
            if (Object.prototype.toString.call(eventType) === '[object Array]') {
                for (i = 0, len = eventType.length; i < len; i++) {
                    this.replay(eventType[i], callback);
                }
                return;
            }
            if (this.history.hasOwnProperty(eventType)) {
                for (i = 0, len = this.history[eventType].length; i < len; i++) {
                    args = this.history[eventType][i];
                    args.unshift(eventType);
                    callback.apply(callback, args);
                }
            }
        }
    };

    return PluginManager;
})(window);


