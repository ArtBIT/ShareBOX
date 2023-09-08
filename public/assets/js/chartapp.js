/**
 * @author 	    Djordje Ungar (djordje@ungar.rs)
 * @copyright   Copyright (c) 2014-2016 Djordje Ungar
 * @version	    1.0.0
 * @package	    ShareBOX
 * @license	http://opensource.org/licenses/MIT	MIT License
 */
(function(window, $) {
    var IS_TOUCH_DEVICE = 'ontouchstart' in window || navigator.maxTouchPoints;
    sharebox.log = function() {
        console.log.apply(console, Array.prototype.slice.call(arguments));
    };
    /*******************/
    /*  T o o l B a r  */
    /*******************/
    function ToolBar(node) {
        this.$ = $(node);
    }
    ToolBar.prototype.addedToPluginManager = function(manager) {
        this.manager = manager;
        this.$.find('button')
        .on('click', $.proxy(this.buttonHandler, this));
    };
    ToolBar.prototype.buttonHandler = function(e) {
        var elem = e.currentTarget;
        var role = elem.getAttribute('role');
        this.manager.trigger('toolbar:click', role);
    };

    /***************/
    /*  C h a r t  */
    /***************/
    function ChartContainer(node, config) {
        this.$ = $(node);
        this.config = config;
        this.chart = new CanvasChart(node, {
            width: this.$.width() - 10
            , height: this.$.height() - 10
            , padding: 90
            , axisPadding: 5
        });
    }
    ChartContainer.prototype.resize = function(width, height) {
        sharebox.log('hello', width, height, this.$);
        width = width || this.$.width();
        height = height || this.$.height();
        this.chart.resize(width, height);
        if (this.chartOverlay) {
            var r = this.chart.getRect('area');
            this.chartOverlay.canvas.width = r.xmax - r.xmin;
            this.chartOverlay.canvas.height = r.ymax - r.ymin;
        }
    };
    ChartContainer.prototype.initChartOverlay = function() {
        var chart = this.chart;

        if (!this.chartOverlay) {
            this.chartOverlay = $('<canvas>');
            this.chartOverlay.canvas = this.chartOverlay.get(0);
            this.$.parent().append(this.chartOverlay);
            this.$.css({position: 'absolute', zIndex: 0});
            this.chartOverlay.css({pointerEvents: 'none'});
        }
        var r = chart.getRect('area');
        this.chartOverlay.canvas.width = r.xmax - r.xmin;
        this.chartOverlay.canvas.height = r.ymax - r.ymin;
        this.chartOverlay.css({position: 'absolute', zIndex: 1, left: r.xmin, top: r.ymin});
    };
    ChartContainer.prototype.drawVerticalCursor = function(x, options) {
        var defaults = {
            color: 'black',
            alpha: 0.2,
            lineWidth: 1
        };
        options = sharebox.extend(defaults, options || {});
        var canvas = this.chartOverlay.canvas;
        var ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.globalAlpha = options.alpha;
        ctx.fillStyle = options.color;
        ctx.fillRect(x, 0, options.lineWidth, canvas.height);
    };
    ChartContainer.prototype.drawHorizontalCursor = function(y, options) {
        var defaults = {
            color: 'black',
            alpha: 0.2,
            lineWidth: 1
        };
        options = sharebox.extend(defaults, options || {});
        var canvas = this.chartOverlay.canvas;
        var ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.globalAlpha = options.alpha;
        ctx.fillStyle = options.color;
        ctx.fillRect(0, y, canvas.width, options.lineWidth);
    };
    ChartContainer.prototype.clearSelection = function() {
        var canvas = this.chartOverlay.canvas;
        var ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    };
    ChartContainer.prototype.addedToPluginManager = function(manager) {
        this.manager = manager;
        this.manager.on('chart:rebuild', $.proxy(this.buildChart, this));
        this.manager.on('chart:unzoom', $.proxy(this.unzoom, this));
        this.manager.on('chart:redraw', $.proxy(this.redraw, this));
        this.manager.on('chart:showgrid', $.proxy(this.showgrid, this));
        this.manager.on('chart:exportimage', $.proxy(this.exportimage, this));
        this.manager.on('chart:exportcsv', $.proxy(this.exportcsv, this));
        this.tooltip = $('<div>', {class: 'chart-tooltip'});
        $('body').append(this.tooltip);
        this.chart.$.on('DOMMouseScroll mousewheel dblclick mousedown mouseup mouseover mousemove mouseout touchstart touchmove touchend', this.mouseHandler.bind(this));
        var that = this;
        $.contextMenu({
            selector: '[role=chart] canvas',
            build: function($trigger, e) {
                var $target = that.chart.$;
                var o = $target.offset();
                var tx = o.left;
                var ty = o.top;
                // Absolute position
                var px = e.pageX;
                var py = e.pageY;
                // Relative position
                var x = px - tx;
                var y = py - ty;

                var selector = 'ul.nav a[role=tab]';
                if (that.chart.getRect('area').containsPoint(x, y)) {
                    return {
                        callback: function(key, options) {
                            switch (key) {
                                case 'reset':
                                    that.manager.trigger('toolbar:click', 'unzoom');
                                    break;
                                case 'save':
                                    that.manager.trigger('chart:exportimage');
                                    break;
                            }
                        },
                        items: {
                            "save":  {name: "Sačuvaj", icon: "add"},
                            "reset":  {name: "Resetuj", icon: "edit"}
                        }
                    };
                } else if (that.chart.getRect('left').containsPoint(x, y)) {
                    return {
                        callback: function(key, options) {
                            if (key == 'edit') {
                                that.manager.trigger('modals:show', 'configure', {onLoad: function($modal) { $modal.find(selector).eq(2).click();}});
                            }
                        },
                        items: {
                            "edit":  {name: "Podesi", icon: "edit"}
                        }
                    };
                } else if (that.chart.getRect('right').containsPoint(x, y)) {
                    return {
                        callback: function(key, options) {
                            if (key == 'edit') {
                                that.manager.trigger('modals:show', 'configure', {onLoad: function($modal) { $modal.find(selector).eq(3).click();}});
                            }
                        },
                        items: {
                            "edit":  {name: "Podesi", icon: "edit"}
                        }
                    };
                } else if (that.chart.getRect('top').containsPoint(x, y)) {
                    return {
                        callback: function(key, options) {
                            if (key == 'edit') {
                                that.manager.trigger('modals:show', 'configure');
                            }
                        },
                        items: {
                            "edit":  {name: "Podesi", icon: "edit"}
                        }
                    };
                } else if (that.chart.getRect('bottom').containsPoint(x, y)) {
                    return {
                        callback: function(key, options) {
                            if (key == 'edit') {
                                that.manager.trigger('modals:show', 'configure', {onLoad: function($modal) { $modal.find(selector).eq(1).click();}});
                            }
                        },
                        items: {
                            "edit":  {name: "Podesi", icon: "edit"},
                        }
                    };
                }

                /*
                        "cut":   {name: "Cut", icon: "cut"},
                        "copy":  {name: "Copy", icon: "copy"},
                        "paste": {name: "Paste", icon: "paste"},
                        "delete": {name: "Delete", icon: "delete"},
                        "sep1": "---------",
                        "quit": {name: "Quit", icon: function($element, key, item){ return 'context-menu-icon context-menu-icon-quit'; }}
                */
            }
        });
    };
    ChartContainer.prototype.showgrid = function(axisgrid) {

        switch (axisgrid) {
            case 'y0_grid':
            case 'y1_grid':
                break;
            default:
                // illegal axis grid
                return;
        }

        this.config.data.y0_grid = false;
        this.config.data.y1_grid = false;
        this.config.data[axisgrid] = true;

        this.redraw();
    };
    var getEventCoords = function(evt){
        var out = {x:0, y:0};
        var type = ' '+evt.type+' ';
        if (' touchstart touchmove touchend touchcancel '.indexOf(type) >= 0) {
            var touch = evt.originalEvent.touches[0] || evt.originalEvent.changedTouches[0];
            out.x = touch.pageX;
            out.y = touch.pageY;
        } else if (' DOMMouseScroll mousewheel mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave '.indexOf(type) >= 0) {
            out.x = evt.pageX;
            out.y = evt.pageY;
        }
        return out;
    };
    ChartContainer.prototype.mouseHandler = function(evt) {
        // Container position
        var $target = this.chart.$;
        var o = $target.offset();
        var tx = o.left;
        var ty = o.top;
        // Absolute position
        var page = getEventCoords(evt);
        var px = page.x;
        var py = page.y;
        // Relative position
        var x = px - tx;
        var y = py - ty;
        if (!this.lastMousePosition) {
            this.lastMousePosition = {
                x: px
                ,y: py
            };
        }
        var areaRect = this.chart.getRect('area');
        var point;
        var value;
        var coords;
        var dy = py - this.lastMousePosition.y;
        var dx = px - this.lastMousePosition.x;
        // relative
        var ry;
        var rx;
        // tmpRect;
        var tmpRect;

        var inChartRect = this.mouseDrag == 'chart' || areaRect.containsPoint(x,y);
        var inTopRect = this.mouseDrag == 'top' || this.chart.getRect('top').containsPoint(x, y);
        var inLeftRect = this.mouseDrag == 'left' || this.chart.getRect('left').containsPoint(x, y);
        var inRightRect = this.mouseDrag == 'right' || this.chart.getRect('right').containsPoint(x, y);
        var inBottomRect = this.mouseDrag == 'bottom' || this.chart.getRect('bottom').containsPoint(x, y);

        this.tooltip.css({top: py - this.tooltip.height()/2 -10});
        switch (evt.type) {
            case 'mousemove':
            case 'touchmove':
                if (inChartRect) {
                    var data = this.chart.getDataPointClosestTo(x, y);

                    this.tooltip.css('left', px + 20);

                    var content = [];
                    if (this.dragAxis) {
                        if (this.dragAxis.drag == 'east' || this.dragAxis.drag == 'west') {
                            this.drawVerticalCursor(data.x.relative, {alpha: 0.5, lineWidth: 2});
                            // tooltip
                            point = data.x;
                            content.push(this.chart.axes.x.label() + ': ' + point.formatted);
                        } else if (this.dragAxis.drag == 'north' || this.dragAxis.drag == 'south') {
                            this.drawHorizontalCursor(py - this.chartOverlay.offset().top, {alpha: 0.5, lineWidth: 2});

                            // relative y value 0..1
                            ry = (py - this.chartOverlay.offset().top) / (areaRect.ymax - areaRect.ymin);
                            // tooltip
                            sharebox.each(['y0','y1'], function(axis_name) {
                                var axis = this.chart.axes[axis_name];
                                content.push(axis.label() + ': ' + axis._formatCallback(axis.unscale(1-ry)));
                            }.bind(this));

                        }
                    } else {
                        // move chart
                        if (this.mouseDrag) {
                            ry = dy/(areaRect.ymax - areaRect.ymin);
                            if (Math.abs(dy) < 0.01) {
                                ry = 0;
                            }
                            rx = dx/(areaRect.xmax - areaRect.xmin);
                            if (Math.abs(dx) < 0.01) {
                                rx = 0;
                            }
                            this.tooltip.hide();
                            this.clearSelection();
                            this.manager.trigger('config:axis:drag', 'all', [rx, ry]);
                        }
                        else {
                            if (data.x) {
                                sharebox.each(data, function(point, label) {
                                    content.push(point.label + ': ' + point.formatted);
                                });
                                this.drawVerticalCursor(data.x.relative);
                            }
                        }
                    }

                    if (content.length) {
                        this.tooltip.html(content.join('<br>'));
                        this.tooltip.show();
                    } else {
                        this.tooltip.hide();
                    }
                }
                else {
                    this.tooltip.hide();
                    this.clearSelection();

                    if (!this.dragAxis) {
                        this.chart.canvas.style.cursor = 'crosshair';
                    }
                    if (inLeftRect) {
                        this.chart.canvas.style.cursor = 'e-resize';
                        this.drawVerticalCursor(0, {alpha: 0.5, lineWidth: 2});

                        if (this.mouseDrag) {
                            tmpRect = this.chart.getRect('left');
                            ry = dy/(tmpRect.ymax - tmpRect.ymin);
                            if (Math.abs(dy) < 0.01) {
                                ry = 0;
                            }
                            this.manager.trigger('config:axis:drag', 'y0', ry);
                        }
                    } else if (inRightRect) {
                        this.chart.canvas.style.cursor = 'w-resize';
                        this.drawVerticalCursor(this.chartOverlay.width()-2, {alpha: 0.5, lineWidth: 2});

                        if (this.mouseDrag) {
                            tmpRect = this.chart.getRect('right');
                            ry = dy/(tmpRect.ymax - tmpRect.ymin);
                            if (Math.abs(dy) < 0.01) {
                                ry = 0;
                            }
                            this.manager.trigger('config:axis:drag', 'y1', ry);
                        }
                    } else if (inTopRect) {
                        this.chart.canvas.style.cursor = 's-resize';
                        this.drawHorizontalCursor(0, {alpha: 0.5, lineWidth: 2});
                    } else if (inBottomRect) {
                        this.chart.canvas.style.cursor = 'n-resize';
                        this.drawHorizontalCursor(this.chartOverlay.height()-2, {alpha: 0.5, lineWidth: 2});

                        if (this.mouseDrag) {
                            tmpRect = this.chart.getRect('bottom');
                            rx = dx/(tmpRect.xmax - tmpRect.xmin);
                            if (Math.abs(dx) < 0.01) {
                                rx = 0;
                            }
                            this.manager.trigger('config:axis:drag', 'x', rx);
                        }
                    }
                }

                break;
            case 'mouseout':
                this.tooltip.hide();
                this.dragAxis = false;
                this.mouseDrag = false;
                break;
            case 'mouseover':
                this.tooltip.show();
                break;
            case 'mousedown':
            case 'touchstart':
                this.mouseDrag = true;
                if (!inChartRect) {
                    if (inLeftRect) {
                        // drag left axis
                        this.dragAxis = {
                            drag: 'east',
                            x: areaRect.xmin + tx,
                            y: py
                        };
                        this.mouseDrag = 'left';
                    } else if (inRightRect) {
                        // drag right axis
                        this.dragAxis = {
                            drag: 'west',
                            x: areaRect.xmax + tx,
                            y: py
                        };
                        this.mouseDrag = 'right';
                    } else if (inTopRect) {
                        this.dragAxis = {
                            drag: 'south',
                            x: px,
                            y: areaRect.ymin + ty,
                        };
                        this.mouseDrag = 'top';
                    } else if (inBottomRect) {
                        this.dragAxis = {
                            drag: 'north',
                            x: px,
                            y: areaRect.ymax + ty,
                        };
                        this.mouseDrag = 'bottom';
                    }
                } else {
                    this.mouseDrag = 'chart';
                }
                break;
            case 'dblclick':

                if (inChartRect) {
                    this.manager.trigger('toolbar:click', 'unzoom');
                }
                else {
                    var selector = 'ul.nav a[role=tab]';
                    if (inLeftRect) {
                        this.manager.trigger('modals:show', 'configure', {onLoad: function($modal) { $modal.find(selector).eq(2).click();}});
                    } else if (inRightRect) {
                        this.manager.trigger('modals:show', 'configure', {onLoad: function($modal) { $modal.find(selector).eq(3).click();}});
                    } else if (inBottomRect) {
                        this.manager.trigger('modals:show', 'configure', {onLoad: function($modal) { $modal.find(selector).eq(1).click();}});
                    }
                }
                this.tooltip.hide();
                this.dragAxis = false;
                this.mouseDrag = false;
                break;
            case 'DOMMouseScroll':
            case 'mousewheel':
                var amount = 0;
                if (evt.originalEvent.wheelDelta) {
                    amount = -evt.originalEvent.wheelDelta;
                } else if (evt.originalEvent.detail) {
                    amount = evt.originalEvent.detail;
                }
                if (amount) {
                    if (inChartRect) {
                        this.manager.trigger('config:axis:scale', 'all', amount);
                    }
                    else {
                        if (inLeftRect) {
                            this.manager.trigger('config:axis:scale', 'y0', amount);
                        } else if (inRightRect) {
                            this.manager.trigger('config:axis:scale', 'y1', amount);
                        } else if (inBottomRect) {
                            this.manager.trigger('config:axis:scale', 'x', amount);
                        }
                    }
                }
                evt.preventDefault();
                break;
            case 'mouseup':
            case 'touchend':
                if (this.dragAxis && inChartRect) {
                    // Point and rect have to have same coordinate space
                    // areaRect has the same coordinate space as the canvas
                    coords = areaRect.toPercentageCoords(x, y);
                    if (this.dragAxis.drag == 'east' || this.dragAxis.drag == 'west') {
                        value = coords[0];
                    } else {
                        value = coords[1];
                    }
                    this.manager.trigger('config:axis', this.dragAxis.drag, value);
                }
                this.tooltip.hide();
                this.dragAxis = false;
                this.mouseDrag = false;
                break;
        }

        this.lastMousePosition = {
            x: px
            ,y: py
        };

        if (IS_TOUCH_DEVICE) {
            if (inChartRect
            || inLeftRect
            || inRightRect
            || inTopRect
            || inBottomRect) {
                evt.preventDefault();
                return false;
            }
        }
    };
    ChartContainer.prototype.exportimage = function() {
        var width = this.chart.canvas.width;
        var height = this.chart.canvas.height;
        var canvas = document.createElement('canvas');
        canvas.width = width * 2;
        canvas.height = height * 2;
        document.body.appendChild(canvas);
        // fill with background color
        var ctx = canvas.getContext('2d');
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(this.chart.canvas, 0, 0, width, height, 0, 0, width * 2, height * 2);

        // get the canvas data
        var imgData = canvas.toDataURL('image/png');
        canvas.parentNode.removeChild(canvas);

        // trigger a download
        var anchor = document.createElement('a');
        anchor.href = imgData;
        anchor.download = 'grafikon.png';
        anchor.click();
    };
    ChartContainer.prototype.exportcsv = function() {
        var merenje = sharebox.get('page.merenje');

        // trigger a download
        var anchor = document.createElement('a');
        anchor.href = '/api/v1/merenja/'+merenje.id+'/redovi?format=csv';
        anchor.download = merenje.name + '.csv';
        anchor.click();
    };
    ChartContainer.prototype.unzoom = function() {
        this.chart.unzoom();
    };
    ChartContainer.prototype.setSeries = function(series) {
        // series has the following format
        // series = [
        //     {
        //         name: {{string name}},
        //         color: {{hexcolor string}},
        //         data: {
        //            {{timestamp}}: {{value}}
        //         }
        //     }, ...
        // ];
        // and we need to calculate the domain (min.max) of the x and y axes
        var dataSeries = {};
        var x_min = Number.MAX_VALUE;
        var x_max = Number.MIN_VALUE;
        this.chart.removeSeries();
        for (var i in series) {
            if (series.hasOwnProperty(i)) {
                var s = series[i];
                var y_min = Number.MAX_VALUE;
                var y_max = Number.MIN_VALUE;
                var value;
                for (var ts in s.data) {
                    if (s.data.hasOwnProperty(ts)) {
                        ts = parseInt(ts, 10);
                        if (ts > x_max) x_max = ts;
                        if (ts < x_min) x_min = ts;
                        value = parseFloat(s.data[ts]);
                        if (value > y_max) y_max = value;
                        if (value < y_min) y_min = value;
                    }
                }
                if (!this.config.data['y'+i+'_color']) {
                    this.config.data['y'+i+'_color'] = s.color;
                }
                if (!this.config.data['y'+i+'_line_type']) {
                    this.config.data['y'+i+'_line_type'] = s.line_type;
                }
                dataSeries['y'+i] = this.chart.addSeries('y'+i, s.data, {
                    lineWidth: 2
                    ,yaxis: this.chart.axes['y'+i].domain([y_min, y_max]).zoom(1).label(s.name)
                    ,name: s.name
                    ,lineType: 'solid'
                    ,color: this.config.data['y'+i+'_color']
                });
            }
        }
        this.chart.axes.x.domain([x_min, x_max]).label(this.config.data.x_title);
        dataSeries.y0.option('xaxis', this.chart.axes.x);
        dataSeries.y1.option('xaxis', this.chart.axes.x);
        this.chart.refresh();
        return dataSeries;
    };
    ChartContainer.prototype.buildChart = function(series) {
        var config = this.config.data;
        var formatter = function(time) {
            return Number(time/1000).toFixed(3).toString().replace(/\./g, ',');
        };
        this.is_dynamic = sharebox.get('page.type') == 'dinamicka';
        this.series = this.setSeries(series);
        this.chart.axes.x.format(formatter);
        window.chart = this.chart;
        this.redraw();
    };
    ChartContainer.prototype.redraw = function() {
        var config = this.config.data;

        var text = config.title;
        if (config.show_name) {
            text += ' ' + sharebox.get('page.merenje.description');
        }
        this.chart.option('header', text);

        text = config.footer;
        if (config.show_date) {
            text += ' ' + sharebox.get('page.merenje.created');
        }
        this.chart.option('footer', text);

        this.series.y0.option({color: config.y0_color, name: config.y0_title, lineType: config.y0_line_type});
        this.series.y1.option({color: config.y1_color, name: config.y1_title, lineType: config.y1_line_type});

        var extent;
        var axis = this.chart.axes.x;
        axis.label(config.x_title);
        var xdomain = axis.domain();
        if (config.x_automatic != '1') {
            if (config.x_min !== undefined) { xdomain[0] = parseInt(config.x_min, 10); }
            if (config.x_max !== undefined) { xdomain[1] = parseInt(config.x_max, 10); }
            if (config.x_inc !== undefined) { this.chart.axes.x.ticksIncrement(parseFloat(config.x_inc)); }
            axis.zoom(xdomain);
        } else {
            config.x_min = parseInt(xdomain[0], 10);
            config.x_max = parseInt(xdomain[1], 10);
            axis.unzoom();
            config.x_inc = axis.automaticTicks();
        }

        axis = this.chart.axes.y0;
        axis.label(config.y0_title);
        var y0domain = axis.domain();
        if (config.y0_automatic != '1') {
            if (config.y0_min !== undefined) { y0domain[0] = parseFloat(config.y0_min); }
            if (config.y0_max !== undefined) { y0domain[1] = parseFloat(config.y0_max); }
            if (config.y0_inc !== undefined) { this.chart.axes.y0.ticksIncrement(parseFloat(config.y0_inc)); }
            axis.zoom(y0domain);
        } else {
            config.y0_min = y0domain[0];
            config.y0_max = y0domain[1];
            axis.unzoom();
            config.y0_inc = axis.automaticTicks();
        }
        axis.option('grid', config.y0_grid);

        axis = this.chart.axes.y1;
        axis.label(config.y1_title);
        var y1domain = axis.domain();
        if (config.y1_automatic != '1') {
            if (config.y1_min !== undefined) { y1domain[0] = parseFloat(config.y1_min); }
            if (config.y1_max !== undefined) { y1domain[1] = parseFloat(config.y1_max); }
            if (config.y1_inc !== undefined) { this.chart.axes.y1.ticksIncrement(parseFloat(config.y1_inc)); }
            axis.zoom(y1domain);
        } else {
            config.y1_min = y1domain[0];
            config.y1_max = y1domain[1];
            axis.unzoom();
            config.y1_inc = axis.automaticTicks();
        }
        axis.option('grid', config.y1_grid);
        this.chart.refresh();
        //this.manager.trigger('config:update', $.proxy(this.onConfigUpdate, this));
        this.chart.render();
        this.initChartOverlay();
    };


    /*****************************/
    /*  M o d a l M a n a g e r  */
    /*****************************/
    function ModalManager(node) {
        this.$ = $(node);
    }
    ModalManager.prototype.addedToPluginManager = function(manager) {
        this.manager = manager;
        this.manager.on('modals:show', $.proxy(this.showModal, this));
    };
    ModalManager.prototype.showModal = function(modal, options) {
        var $modal = this.$.find('[role="'+modal+'"]').clone();
        $modal.dialog({
            modal: true,
            resizable: true,
            width: $modal.data('width') || 'auto',
            height: $modal.data('height') || 'auto'
        });
        $modal.data('name', modal);
        this.manager.trigger('modals:shown', $modal, options);
    };


    /*******************************/
    /*  C o n f i g u r a t i o n  */
    /*******************************/
    function Configuration(config) {
        var defaults = {
            title: '',
            name: sharebox.get('page.merenje.name') || '',
            show_name: true,
            footer: '',
            show_date: false,

            x_title: 'Vreme [s]',
            x_automatic: true,
            x_min: '',
            x_max: '',
            x_inc: 1,

            y0_title: 'Protok [l/min]',
            y0_automatic: true,
            y0_color: false,
            y0_line_type: 'solid',
            y0_min: '',
            y0_max: '',
            y0_inc: 10,
            y0_grid: true,

            y1_title: 'Pritisak [bar]',
            y1_automatic: true,
            y1_color: false,
            y1_line_type: 'solid',
            y1_min: '',
            y1_max: '',
            y1_inc: 0.1,
            y1_grid: false
        };
        this.data = sharebox.extend(defaults, config || {});
    }
    Configuration.prototype.bindToForm = function($form) {
        this.$form = $form;
        this.$form.find('input, select').change($.proxy(this.onFormChange, this));
        this.updateForm();
    };
    Configuration.prototype.updateForm = function() {
        var that = this;
        $.each(this.data, function(key, value){
            var $ctrl = that.$form.find('[name='+key+']');
            switch ($ctrl.prop('nodeName'))
            {
                case 'INPUT':
                    switch ($ctrl.attr('type')) {
                        case 'radio':
                            $ctrl.each(function(){
                               if ($(this).attr('value') == value) {
                                   $(this).prop('checked', value);
                               }
                            });
                            break;
                        case 'checkbox':
                            $ctrl.prop('checked', value == '1');
                            break;
                        default:
                            $ctrl.val(value);
                    }
                    break;
                case 'SELECT':
                    $ctrl.selectpicker('val', value);
                    break;
                default:
                    $ctrl.val(value);

            }
        });
    };
    Configuration.prototype.onFormChange = function() {
        var that = this;
        var config = {};
        $.each(this.data, function(key, value) {
            var $ctrl = that.$form.find('[name='+key+']');
            if (!$ctrl.length) {
                // nothing to do
                return;
            }
            switch ($ctrl.prop('nodeName'))
            {
                case 'INPUT':
                    switch ($ctrl.attr('type')) {
                        case 'radio':
                            that.data[key] = null;
                            $ctrl.each(function(){
                                if ($ctrl.prop('checked')) {
                                    that.data[key] = $ctrl.val();
                                    return false;
                                }
                            });
                            break;
                        case 'checkbox':
                            that.data[key] = $ctrl.prop('checked') ? $ctrl.val() : null;
                            break;
                        default:
                            that.data[key] = $ctrl.val();
                    }
                    break;
                default:
                    that.data[key] = $ctrl.val();
            }
        });
    };
    Configuration.prototype.update = function(config) {
        config = config || {};
        for (var c in config) {
            if (config.hasOwnProperty(c)) {
                if (this.data.hasOwnProperty(c)) {
                    this.data[c] = config[c];
                }
            }
        }
    };
    

    /***********************/
    /*  C h a r t   A p p  */
    /***********************/
    function ChartApp(node, chart_data) {
        log = this.log;
        this.$ = $(node);
        this.config = new Configuration();
        this.plugins = new PluginManager();

        this.chartContainer = new ChartContainer(this.$.find('[role=chart]').get(0), this.config);
        this.chart = this.chartContainer.chart;

        this.plugins.add(this.chartContainer);
        this.plugins.add(new ToolBar(this.$.find('[role=toolbar]').get(0)));
        this.plugins.add(new ModalManager(this.$.find('[role=modals]').get(0)));

        this.plugins.on('toolbar:click', $.proxy(this.onToolBarClick, this));
        this.plugins.on('modals:shown', $.proxy(this.onModalInit, this));
        this.plugins.on('config:update', $.proxy(this.onConfigUpdate, this));
        this.plugins.on('config:axis', $.proxy(this.onConfigAxis, this));
        this.plugins.on('config:axis:scale', $.proxy(this.onScaleAxis, this));
        this.plugins.on('config:axis:drag', $.proxy(this.onDragAxis, this));

        this.bindEvents();
        this.setChartData(chart_data);
    }
    ChartApp.prototype.onToolBarClick = function(role) {
        switch(role) {
            case 'configure':
            case 'filter':
            case 'table':
                this.plugins.trigger('modals:show', role);
                break;
            case 'export':
                this.plugins.trigger('chart:exportcsv');
                break;
            case 'image':
                this.plugins.trigger('chart:exportimage');
                break;
            case 'unzoom':
                this.plugins.trigger('chart:unzoom');
                this.config.update({
                    x_automatic: '1'
                    ,y0_automatic: '1'
                    ,y1_automatic: '1'
                });
                this.configChanged();
                break;
            case 'y0_grid':
            case 'y1_grid':
                this.plugins.trigger('chart:showgrid', role);
                break;
        }
    };
    ChartApp.prototype.log = function() {
        var args = Array.prototype.slice.call(arguments);
        args.unshift('[ShareBox]');
    };
    ChartApp.prototype.setChartData = function(series) {
        this.series = series;
        this.rebuildChart();
    };
    ChartApp.prototype.rebuildChart = function() {
        this.plugins.trigger('chart:rebuild', this.series);
    };
    ChartApp.prototype.configChanged = function() {
        this.plugins.trigger('chart:redraw');
    };
    ChartApp.prototype.onConfigUpdate = function(config) {
        this.config.update(config);
    };
    ChartApp.prototype.onDragAxis = function(axis, value) {
        if (axis == 'all') {
            sharebox.each(['x','y0','y1'], function(axis) {
                if (axis == 'x') {
                    this.onDragAxis(axis, value[0]);
                } else {
                    this.onDragAxis(axis, value[1]);
                }
            }.bind(this));
            return;
        }
        if (axis == 'x') {
            value *= -1;
        }
        var config = this.config.data;
        var min = parseFloat(config[axis+'_min']);
        var max = parseFloat(config[axis+'_max']);
        var dx = max - min;
        var px = value * dx;
        min += px;
        max += px;
        config[axis+'_min'] = min;
        config[axis+'_max'] = max;
        config[axis+'_automatic'] = 0;
        this.config.update(config);
        this.configChanged();
    };
    ChartApp.prototype.onScaleAxis = function(axis, value) {
        if (axis == 'all') {
            sharebox.each(['x','y0','y1'], function(axis) {
                this.onScaleAxis(axis, value);
            }.bind(this));
            return;
        }
        var config = this.config.data;
        var min = parseFloat(config[axis+'_min']);
        var max = parseFloat(config[axis+'_max']);
        var dx = max - min;
        var inc = dx/ 10;
        var px = dx * 0.1;
        if (value > 0) {
            max += px;
            min -= px;
        } else {
            max -= px;
            min += px;
        }
        // x axis values are ints
        if (axis == 'x') {
            max = max | 0;
            min = min | 0;
            inc = inc | 0;
        }
        config = {};
        config[axis+'_min'] = min;
        config[axis+'_max'] = max;
        config[axis+'_automatic'] = 0;
        config[axis+'_inc'] = inc;
        this.config.update(config);
        this.configChanged();
    };
    ChartApp.prototype.onConfigAxis = function(drag, value) {
        var config = false;
        var x_value;
        var y0_value;
        var y1_value;
        if (drag == 'east') {
            config = {};
            config.x_automatic = 0;
            x_value = niceRound(this.chart.axes.x.unscale(value), 250);
            config.x_min = x_value;
            config.x_inc = Math.max(250, niceRound((parseFloat(this.config.data.x_max) - x_value) / 10, 250));
        } else if (drag == 'west') {
            config = {};
            config.x_automatic = 0;
            x_value = niceRound(this.chart.axes.x.unscale(value), 250);
            config.x_max = x_value;
            config.x_inc = Math.max(250, niceRound((x_value - parseFloat(this.config.data.x_min)) / 10, 250));
        } else if (drag == 'south') {
            config = {};
            config.y0_automatic = 0;
            y0_value = this.chart.axes.y0.unscale(1-value);
            config.y0_max = y0_value;
            config.y0_inc = Math.max(0.1, niceRound((y0_value - parseFloat(this.config.data.y0_min)) / 10, 0.1));

            config.y1_automatic = 0;
            y1_value = this.chart.axes.y1.unscale(1-value);
            config.y1_max = y1_value;
            config.y1_inc = Math.max(0.01, niceRound((y1_value - parseFloat(this.config.data.y1_min)) / 10, 0.01));
        } else if (drag == 'north') {
            config = {};
            config.y0_automatic = 0;
            y0_value = this.chart.axes.y0.unscale(1-value);
            config.y0_min = y0_value;
            config.y0_inc = Math.max(0.1, niceRound((parseFloat(this.config.data.y0_max) - y0_value) / 10, 0.1));

            config.y1_automatic = 0;
            y1_value = this.chart.axes.y1.unscale(1-value);
            config.y1_min = y1_value;
            config.y1_inc = Math.max(0.01, niceRound((parseFloat(this.config.data.y1_max) - y1_value) / 10, 0.01));
        }
        this.config.update(config);
        this.configChanged();
    };
    
    ChartApp.prototype.onModalInit = function($modal, options) {
        options = options || {};
        var init = sharebox.get('sharebox.modals.init.' + $modal.data('name'));
        if (init) {
            init($modal);
        }
        if ($modal.data('name') == 'configure') {
            this.config.bindToForm($modal);
        }
        $modal.on('dialogclose', function(event, ui) {
            $modal.remove();
        });
        if (options.onLoad) {
            options.onLoad($modal);
        }
    };

    ChartApp.prototype.bindEvents = function() {
        var throttledResizeHandler = sharebox.debounce(this.onResize.bind(this), 300);
        window.addEventListener('resize', throttledResizeHandler);
    };

    ChartApp.prototype.onResize = function() {
        sharebox.log("On Resize", this);
        this.chartContainer.resize();
    };

    window.ChartApp = ChartApp;
})(window, jQuery);

