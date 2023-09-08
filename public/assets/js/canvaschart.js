/**
 * @author 	    Djordje Ungar (djordje@ungar.rs)
 * @copyright   Copyright (c) 2014-2016 Djordje Ungar
 * @version	    1.0.0
 * @package	    ShareBOX
 * @license	http://opensource.org/licenses/MIT	MIT License
 */
;CanvasChart = (function(window) {

    var FONT_SIZE = 11;
    var FONT_UNITS = 'px';
    var FONT_FAMILY = 'Sans';
    var FONT_PADDING = 10;

    var noop = function() {};
    var norm = function(value, min, max) {
        return (value - min) / (max - min);
    };
    var lerp = function(norm, min, max) {
        return (max - min) * norm + min;
    };
    var map = function(value, sourceMin, sourceMax, destMin, destMax) {
        return lerp(norm(value, sourceMin, sourceMax), destMin, destMax);
    };
    var clamp = function(value, min, max) {
        return Math.max(Math.min(min,max), Math.min(Math.max(min, max), value));
    };
    var distance = function(x0, y0, x1, y1) {
        var dx = x1 - x0,
            dy = y1 - y0;
        return Math.sqrt(dx * dx + dy * dy);
    };
    var randomRange = function(min, max) {
        return random() * (max - min) + min;
    };
    var randomInt = function(min, max) {
        return (random() * (max - min + 1) + min)|0;
    };
    /* normal distribution */
    var randomDist = function(min, max, iterations) {
        if (iterations > 0) {
            var total = 0, 
                i = iterations;
            while (i--) {
                total += utils.randomRange(min, max);
            }
            return total / iterations;
        }
        return 0;
    };
    var snapTo = function(value, multiplier) {
        return ((value / multiplier) | 0) * multiplier;
    };
    var isArray = function(o) {
        return Object.prototype.toString.call(o) === '[object Array]';
    };
    var isNumeric = function(o) {
        return !isNaN(parseFloat(o)) && isFinite(o);
    };
    var isInteger = function(n) {
       return isNumeric(n) && n % 1 === 0;
    };
    var isFloat = function(n) {
       return isNumeric(n) && n % 1 !== 0;
    };
    var getKeyClosestTo = function(val, array) {
        if (array[val] !== undefined) {
            return val;
        } else {
            var upper = val;
            var upperMatched = false;
            var lower = val;
            var lowerMatched = false;

            var keys = Object.keys(array);
            while(upper < keys.length) {
                if (array[upper] !== undefined) {
                    upperMatched = true;
                    break;
                }
                upper++;
            }

            while(lower > -1) {
                if (array[lower] !== undefined) {
                    lowerMatched = true;
                    break;
                }
                lower--;
            }

            if (upperMatched && lowerMatched) {
                return (upper - val) < (val - lower) ? upper : lower;
            } else if (upperMatched) {
                return upper;
            } else if (lowerMatched) {
                return lower;
            }
        }

        return undefined;
    };

    var setContextLineType = function(ctx, type) {
        switch (type) {
            case 'dashed':
                ctx.setLineDash([4, 4]);
                break;
            case 'dotted':
                ctx.setLineDash([2, 2]);
                break;
        }
    };

    function Rectangle(x, y, width, height) {
        return this.reset(x, y, width, height);
    }
    Rectangle.prototype.reset = function(x, y, width, height) {
        this.size(width, height);
        this.position(x, y);
        return this;
    };
    Rectangle.prototype.size = function(width, height) {
        this.width = parseFloat(width || 0);
        this.height = parseFloat(height || 0);
        this.xmax = this.x + this.width;
        this.ymax = this.y + this.height;
        return this;
    };
    Rectangle.prototype.position = function(x, y) {
        this.x = this.xmin = x = parseFloat(x || 0);
        this.y = this.ymin = y = parseFloat(y || 0);
        this.xmax = this.xmin + this.width;
        this.ymax = this.ymin + this.height;
        return this;
    };
    Rectangle.prototype.toArray = function() {
        return [this.x, this.y, this.width, this.height];
    };
    Rectangle.prototype.toString = function() {
        return '[Rectangle ('+this.toArray()+')]';
    };
    Rectangle.prototype.containsPoint = function(x, y) {
        return x >= this.xmin
            && x <= this.xmax
            && y >= this.ymin
            && y <= this.ymax;
    };
    Rectangle.prototype.toRelativeCoords = function(x, y) {
        return [x-this.xmin, y-this.ymin];
    };
    Rectangle.prototype.toPercentageCoords = function(x, y) {
        return [(x-this.xmin)/(this.xmax-this.xmin), (y-this.ymin)/(this.ymax-this.ymin)];
    };

    /********/
    /* BASE */
    /********/
    function BaseObject(defaultOptions, options) {
        this._options = sharebox.extend(defaultOptions || {}, options || {});
        this._rects = {};
    }
    BaseObject.prototype.option = function(name, value) {
        if (arguments.length == 1) {
            // retrieve an option
            if (typeof name == 'string') {
                return this._options[name];
            }
            // set options from object
            sharebox.each(name, function(value, option) {
                this.option(option, value);
            }.bind(this));
            return;
        }
        if (this._options.hasOwnProperty(name)) {
            this._options[name] = value;
        }
        return this._options[name];
    };
    BaseObject.prototype.getRect = function(name) {
        if (!this._rects.hasOwnProperty(name)) {
            this.setRect(name, new Rectangle(0, 0, 1, 1));
        }
        return this._rects[name];
    };
    BaseObject.prototype.setRect = function(name, rect) {
        if (!(rect instanceof Rectangle)) {
            throw new Error('Must be of instance Rectangle');
        }
        this._rects[name] = rect;
        return this._rects[name];
    };
    /***************/
    /* DATA SERIES */
    /***************/
    function DataSeries(data, options) {
        var defaultOptions = {
            color: '#F00'
            , name: ''
            , lineWidth: 1
            , lineType: 'solid'
            , xaxis: false
            , yaxis: false
        };
        BaseObject.call(this, defaultOptions, options);
        this.data = data;
    }
    DataSeries.prototype = new BaseObject();
    DataSeries.constructor = DataSeries;

    DataSeries.prototype.getDataPointClosestTo = function(x, y) {
        var dx = getKeyClosestTo((this._options.xaxis.unscale(x)+0.5)|0, this.data);
        if (dx !== undefined) {
            return {
                x: dx,
                y: this.data[dx]
            };
        }
        return undefined;
    };
    DataSeries.prototype.render = function(ctx, x, y, width, height) {
        ctx.save();
        ctx.beginPath();
        ctx.rect(x, y, width, height);
        ctx.clip();

        ctx.translate(x, y);
        ctx.lineWidth = this._options.lineWidth;
        ctx.strokeStyle = this._options.color;
        setContextLineType(ctx, this._options.lineType);
        ctx.beginPath();

        sharebox.each(this.data, (function(that) {
            var previous = false;
            var inDomain = false;
            var last_x_drawn = -1;
            var movePencil = function(x, y, draw) {
                var dx = (x * width + 0.5)|0;
                var dy = ((1-y) * height + 0.5)|0;

                // Only move or draw the pencil if x was incremented by 1px or more
                if ((dx - last_x_drawn) < 1) {
                    return;
                }
                last_x_drawn = dx;
                if (draw) {
                    ctx.lineTo(dx, dy);
                } else {
                    ctx.moveTo(dx, dy);
                }
            };
            return function(y, x) {
                var px = that._options.xaxis.scale(x);
                var py = that._options.yaxis.scale(y);
                if (px >=0 && px <= 1) {
                    inDomain = true;
                    if (py >= 0 && py <= 1) {
                        if (!previous) {
                            movePencil(px, py);
                        } else {
                            movePencil(previous[0], previous[1]);
                        }
                    }
                    movePencil(px, py, true);
                } else {
                    if (inDomain) {
                        inDomain = false;
                        movePencil(px, py, true);
                    }
                }
                previous = [px, py];
            };
        })(this));
        ctx.stroke();
        ctx.closePath();
        ctx.restore();
    };

    /********/
    /* AXIS */
    /********/
    window.niceRound = function(x, niceFactor) {
        niceFactor = niceFactor || 2;
        return Math.floor(x/niceFactor) * niceFactor;
        //(Math.pow(niceFactor, Math.round(Math.log(x)/Math.log(niceFactor))));
    };
    var numericFormatter = function(x) {
        return Number(x).toFixed(2).toString().replace(/\./g, ',');
    };
    function Axis(options) {
        options = options || {};
        var defaultOptions = {
            numTicks: 10
            , clamp: false
            , domain: [0, 1]
            , zoom: [0, 1]
            , label: false
            , padding: 0
            , grid: false
        };
        BaseObject.call(this, defaultOptions, options);
        this._domain = this._options.domain;
        this._zoom = false;
        this._ticksIncrement = (options.domain) ? niceRound((this._domain[1] - this._domain[0]) / this._options.numTicks) : false;
        this._label = options.label;
        this.format(this._options.formatter || numericFormatter);
        this._padding = this._options.padding;
    }
    Axis.prototype = new BaseObject();
    Axis.constructor = Axis;
    Axis.prototype.refresh = function() {
        var extent = this._zoom || this._domain;
        var min = extent[0];
        var max = extent[1];

        var interpolate = (function(min, max) {
            return function(x) {
                return map(+x, min, max, 0, 1);
            };
        })(min, max);
        var uninterpolate = (function(min, max) {
            return function(x) {
                return map(+x, 0, 1, min, max);
            };
        })(min, max);

        this.scale = interpolate;
        this.unscale = uninterpolate;
        return this;
    };
    Axis.prototype.automaticTicks = function() {
        var extent = this._zoom || this._domain;
        this._ticksIncrement = (extent[1] - extent[0]) / this._options.numTicks;
        return this._ticksIncrement;
    };
    Axis.prototype.ticksIncrement = function(value) {
        this._ticksIncrement = value;
    };
    // Set the axis label
    Axis.prototype.label = function(label) {
        if (label === undefined) {
            return this._label;
        }
        this._label = label;
        return this;
    };
    // Set the min and max for the axis
    Axis.prototype.domain = function(domain) {
        if (domain === undefined) {
            return this._domain.map(Number);
        }
        this._domain = domain.map(Number);
        this.refresh();
        return this;
    };
    // set the axis' visible min and max range
    Axis.prototype.zoom = function(zoom) {
        if (zoom === undefined) {
            return this._zoom.map(Number);
        }
        if (isNumeric(zoom)) {
            // if it's number, scale the domain
            var med = (this._domain[1] - this._domain[0])/2 + this._domain[0];
            if (this._domain[0] == this._domain[1]) {
                this._domain[0] -= 0.1;
                this._domain[1] += 0.1;
            }
            this._zoom = [
                med - (med - this._domain[0]) * zoom
                , med + (med - this._domain[0]) * zoom
            ];
        } else if (isArray(zoom) && zoom.length == 2) {
            // if it's array
            this._zoom = zoom.map(Number);
        } else {
            throw new Error('Invalid zoom');
        }
        this.refresh();
        return this;
    };
    Axis.prototype.unzoom = function() {
        this._zoom = this._domain.map(Number);
        return this;
    };
    Axis.prototype.format = function(callback) {
        this._formatCallback = callback || function(x) { return x; };
        return this;
    };
    Axis.prototype.padding = function(value) {
        if (value === undefined) {
            return this._padding;
        }
        this._padding = parseFloat(value) || 0;
    };
    Axis.prototype.render = function(ctx, x, y, width, height) {
        ctx.save();

        var p = this._padding;
        var gridPadding = 3;
        var tx;
        var ty;
        var metrics;
        // Main Axis line
        ctx.beginPath();
        ctx.strokeStyle = '#000';
        ctx.lineWidth = 1;
        tx = x - p;
        ty = y + height + p;
        ctx.moveTo(tx, ty);
        ctx.lineTo(tx + width + 2*p, ty);
        ctx.stroke();
        ctx.closePath();
        this.getRect('line').reset(tx - gridPadding, ty, width, 2 * gridPadding);
        this.getRect('ticks').reset(tx - gridPadding, y + height + gridPadding, width, FONT_PADDING + p + gridPadding);

        // grid lines
        var extent = this._zoom || this._domain;
        var max = 100;
        var px, py;
        var inc = this._ticksIncrement;
        var i = extent[0];
        ctx.beginPath();
        ctx.strokeStyle = 'rgba(0, 0, 0, 0.5)';
        //ctx.setLineDash([2,3]);
        ctx.lineWidth = 0.25;
        ctx.font = FONT_SIZE + FONT_UNITS + ' ' + FONT_FAMILY;
        ctx.textAlign = 'center';
        ctx.fillStyle = 'black';
        var last = 0;
        while (max-- >= 0 && i <= extent[1]) {
            px = (this.scale(i)*width + x + 0.5)|0;
            py = y;
            // minimum tick distance
            if (last && Math.abs(last - px) < 50) {
                continue;
            }
            last = px;
            ctx.moveTo(px, py - gridPadding);
            if (this._options.grid) {
                ctx.lineTo(px, py + height + p + gridPadding);
            }
            ctx.fillText(this._formatCallback(i), px, py + height + FONT_SIZE + p + gridPadding);

            if (i == extent[1]) {
                break;
            }
            i = Math.min(extent[1], i + inc);
        }
        ctx.stroke();
        ctx.closePath();

        // label
        if (this._label) {
            ctx.font = 1.2*FONT_SIZE + FONT_UNITS + ' ' + FONT_FAMILY;
            tx = x + width/2;
            ty = y + height + 3*FONT_SIZE + p;
            ctx.fillText(this._label, tx, ty);
            metrics = ctx.measureText(this._label);
            this.getRect('label').reset(tx - metrics.width, ty, width, 1.2*FONT_SIZE);
        }

        ctx.restore();
    };

    function XAxis(options) {
        Axis.call(this, options);
    }
    XAxis.prototype = new Axis();
    XAxis.prototype.constructor = XAxis;

    function YAxis(options) {
        XAxis.call(this, options);
    }
    YAxis.prototype = new XAxis();
    YAxis.prototype.constructor = YAxis;
    YAxis.prototype.render = function(ctx, x, y, width, height) {
        ctx.save();

        var p = this._padding;
        // Main axis line
        ctx.beginPath();
        ctx.strokeStyle = 'black';
        ctx.lineWidth = 1;
        if (this._options.secondary) {
            x+=width + p;
        } else {
            x -= p;
        }
        ctx.moveTo(x, y);
        ctx.lineTo(x, y + height + p);
        ctx.stroke();
        ctx.closePath();
        this.getRect('line').reset(x, y, p, height + p);

        // grid lines
        var extent = this._zoom || this._domain;
        var inc = this._ticksIncrement;
        var i = extent[0];
        var max = 200;
        var px, py;
        //ctx.setLineDash([2,3]);
        ctx.font = FONT_SIZE + FONT_UNITS + ' ' + FONT_FAMILY;
        ctx.textBaseline = 'middle';
        ctx.textAlign = 'right';
        ctx.fillStyle = 'black';
        ctx.lineWidth = 0.25;
        var padding = FONT_PADDING;
        var gridPadding = 3;
        var koef = -1;
        if (this._options.secondary) {
            koef = 1;
            ctx.textAlign = 'left';
        }
        var last = 0;
        var maxwidth = Number.MIN_VALUE;
        var text;
        var metrics;
        while (max-- >= 0 && i <= extent[1]) {
            py = ((1-this.scale(i))*height + y + 0.5)|0;
            px = (x + 0.5)|0;

            // minimum tick distance
            if (last && Math.abs(last - py) < 10) {
                continue;
            }
            last = py;

            // ticks
            ctx.beginPath();
            ctx.strokeStyle = '#000000';
            ctx.moveTo(px + koef* gridPadding, py);
            ctx.lineTo(px, py);
            ctx.stroke();
            ctx.closePath();

            if (this._options.grid) {
                // grid lines
                ctx.beginPath();
                ctx.strokeStyle = 'rgba(0, 0, 0, 0.5)';
                ctx.moveTo(px, py);
                if (this._options.secondary) {
                    ctx.lineTo(px - width - 2 * p, py);
                } else {
                    ctx.lineTo(px + width + 2 * p, py);
                }
                ctx.stroke();
                ctx.closePath();
            }
            text = this._formatCallback(i);
            ctx.fillText(text, px + koef * padding, py);
            metrics = ctx.measureText(text);
            maxwidth = Math.max(maxwidth, metrics.width);

            if (i == extent[1]) {
                break;
            }
            i = Math.min(extent[1], i + inc);
        }
        ctx.restore();
        var rectWidth = maxwidth + FONT_PADDING + gridPadding;
        if (this._options.secondary) {
            this.getRect('ticks').reset(x, y, rectWidth, height);
        }
        else {
            this.getRect('ticks').reset(x - rectWidth, y, rectWidth, height);
        }

        if (this._label) {
            ctx.save();
            ctx.textBaseline = 'middle';
            ctx.textAlign = 'center';
            ctx.font = 1.2*FONT_SIZE + FONT_UNITS + ' ' + FONT_FAMILY;
            if (this._options.secondary) {
                ctx.translate(x + 6 * FONT_SIZE, y + height/2);
            } else {
                ctx.translate(x - 6 * FONT_SIZE, y + height/2);
            }
            ctx.rotate(-Math.PI/2);
            ctx.fillText(this._label, 0, 0);
            ctx.restore();
        }
    };
    /**********/
    /* LEGEND */
    /**********/
    function Legend(options) {
        var defaultOptions = {
            variant: 'horizontal'
            ,align: 'center'
            ,baseline: 'middle'
            ,padding: 0
            ,size: 12
        };
        BaseObject.call(this, defaultOptions, options);
        this.series = [];
    }
    Legend.prototype = new BaseObject();
    Legend.prototype.constructor = Legend;
    Legend.prototype.setSeries = function(series) {
        this.series = series;
    };
    Legend.prototype.render = function(context, x, y, w, h) {
        var cursor = {
            x: 0
            ,y: 0
            ,width: 0
            ,height: 0
        };

        var canvas = document.createElement('canvas');
        canvas.width = w;
        canvas.height = h;
        var ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, w, h);

        ctx.textAlign = 'left';
        ctx.textBaseline = 'top';
        ctx.font = this._options.size + FONT_UNITS + ' ' + FONT_FAMILY;

        var size = this.option('size');
        var padding = size >> 1;

        var renderColorLine = function(series) {
            ctx.save();
            ctx.strokeStyle = series.option('color');
            ctx.lineWidth = 2;
            setContextLineType(ctx, series.option('lineType'));
            var cy = cursor.y + (size >> 1);
            ctx.beginPath();
                ctx.moveTo(cursor.x, cy);
                cursor.x += 2*size;
                ctx.lineTo(cursor.x, cy);
            ctx.stroke();
            ctx.closePath();
            ctx.restore();
        };
        var renderer;
        if (this._options.variant == 'horizontal') {
            renderer = function(series) {
                // Render color
                renderColorLine(series);
                // Render label
                cursor.x += padding;
                var text = series.option('name');
                ctx.fillStyle = 'black';
                ctx.fillText(text, cursor.x, cursor.y);
                var metrics = ctx.measureText(text);
                cursor.x += metrics.width + 3*padding;
                cursor.height = size + 2*padding;
                cursor.width = Math.max(cursor.width, cursor.x);
            };
        } else {
            renderer = function(series) {
                // Render color
                renderColorLine(series);
                // Render label
                cursor.x += 2*size + padding;
                var text = series.option('name');
                ctx.fillText(text, cursor.x, cursor.y);
                var metrics = ctx.measureText(text);
                cursor.x = x;
                cursor.y += size + 2*padding;
                cursor.height = Math.max(cursor.height, cursor.y);
                cursor.width = Math.max(cursor.width, metrics.width);
            };
        }

        sharebox.each(this.series, renderer);
        switch (this.option('align')) {
            case 'center':
                x -= cursor.width/2;
                break;
            case 'right':
                x -= cursor.width;
                break;
        }
        switch (this.option('baseline')) {
            case 'middle':
                y -= cursor.height/2;
                break;
            case 'bottom':
                y -= cursor.height;
                break;
        }
        context.drawImage(canvas, 0, 0, cursor.width, cursor.height, x|0, y|0, cursor.width, cursor.height);
    };
    

    /****************/
    /* CANVAS CHART */
    /****************/
    function CanvasChart(node, options) {
        var defaultOptions = {
            width: 800,
            height: 600,
            padding: 80,
            header: false,
            footer: false,
            axisPadding: 5
        };
        BaseObject.call(this, defaultOptions, options);
        options.width = Math.max(800, options.width);
        this.$ = $(node);
        options = this._options;
        this.width = options.width;
        this.height = options.height;
        this.padding = options.padding;
        this.axisPadding = options.axisPadding;
        this.canvas = document.createElement('canvas');
        this.canvas.width = this.width;
        this.canvas.height = this.height;
        this.$.append(this.canvas);
        this.ctx = this.canvas.getContext('2d');
        this.axes = {
            x: new XAxis({grid: true, padding: options.axisPadding}),
            y0: new YAxis({grid: true, padding: options.axisPadding}),
            y1: new YAxis({secondary: true, padding: options.axisPadding})
        };
        this.series = {};
        this.legend = new Legend(options);
        this._options = options;
        return this;
    }
    CanvasChart.prototype = new BaseObject();
    CanvasChart.prototype.constructor = CanvasChart;
    CanvasChart.prototype.resize = function(width, height) {
        this._options.width = this.canvas.width = this.width = width;
        this._options.height = this.canvas.height = this.height = height;
        this.render();
    };
    CanvasChart.prototype.getSeries = function(name) {
        if (arguments.length && this.series.hasOwnProperty(name)) {
            return this.series[name];
        }
        throw new Error('Unknown series "'+name+'"');
    };
    CanvasChart.prototype.removeSeries = function(name) {
        if (arguments.length) {
            delete this.series[name];
            return;
        }
        this.series = {};
        return this;
    };
    CanvasChart.prototype.addSeries = function(name, data, options) {
        this.series[name] = new DataSeries(data, options);
        return this.series[name];
    };
    CanvasChart.prototype.render = function() {
        var that = this;
        var ctx = this.ctx;
        var p = that.padding;
        var w = that.width - 2*p;
        var h = that.height - 2*p;
        var fontSize;
        var area = this.getRect('area');
        ctx.clearRect(0, 0, this.width, this.height); 
        ctx.textAlign = 'center';
        ctx.fillStyle = 'black';
        // render header
        if (this._options.header) {
            fontSize = 1.5*FONT_SIZE;
            ctx.font = fontSize + FONT_UNITS + ' ' + FONT_FAMILY;
            ctx.fillText(this._options.header, that.width >> 1, area.ymin - 3*fontSize);
        }
        // render footer
        if (this._options.footer) {
            fontSize = 1.2*FONT_SIZE;
            ctx.font = fontSize + FONT_UNITS + ' ' + FONT_FAMILY;
            ctx.fillText(this._options.footer, that.width >> 1, that.height - fontSize);
        }

        // render axis
        sharebox.each(this.axes, function(axis) {
            axis.render(ctx, p, p, w, h);
        });
        this.setRect('left', this.axes.y0.getRect('ticks'));
        this.setRect('right', this.axes.y1.getRect('ticks'));
        this.setRect('bottom', this.axes.x.getRect('ticks'));
        this.getRect('top').reset(area.xmin, area.ymin - FONT_SIZE, w, FONT_SIZE);

        // render the series
        sharebox.each(this.series, function(series, name) {
            series.render(ctx, p, p, w, h);
        });

        // render the legend
        this.legend.setSeries(this.series);
        this.legend.render(ctx, (that.width/2)|0, (p - fontSize)|0, 360, 100);
        return this;
    };
    CanvasChart.prototype.getDataPointClosestTo = function(x, y) {
        var r = this.getRect('area');

        var dx = (x - r.xmin)/(r.xmax-r.xmin);
        var dy = (y - r.ymin)/(r.ymax-r.ymin);

        var info = {};
        var axis;
        sharebox.each(this.series, function(series, name) {
            var data = series.getDataPointClosestTo(dx, dy);
            if (data !== undefined) {
                axis = series.option('xaxis');
                info.x = {
                    label: axis.label()
                    , value: data.x
                    , formatted: axis._formatCallback(data.x)
                    , percentage: axis.scale(data.x)
                    , relative: axis.scale(data.x) * (r.xmax-r.xmin)
                };
                axis = series.option('yaxis');
                info[name] = {
                    label: axis.label()
                    , value: data.y
                    , formatted: axis._formatCallback(data.y)
                    , percentage: axis.scale(data.y)
                    , relative: axis.scale(data.y) * (r.ymax-r.ymin)
                };
            }
        });
        return info;
    };
    CanvasChart.prototype.unzoom = function() {
        sharebox.each(this.axes, function(axis) {
            axis.unzoom();
            axis.refresh();
        });
        return this;
    };
    CanvasChart.prototype.recalculateRect = function() {
        // chart area
        var padding = this.padding;
        var xmin = padding;
        var ymin = padding;
        var xmax = this.width - padding;
        var ymax = this.height - padding;

        this.getRect('area').reset(xmin, ymin, xmax - xmin, ymax - ymin);
    };
    CanvasChart.prototype.refresh = function() {
        this.recalculateRect();

        sharebox.each(this.axes, function(axis) {
            axis.refresh();
        });
        return this;
    };
    CanvasChart.prototype.whichRectContains = function(x, y) {
        sharebox.each(this._rects, function(rect, name) {
            if (rect.containsPoint(x, y)) {
                return name;
            }
        });
        return false;
    };
    return CanvasChart;
})(window);

