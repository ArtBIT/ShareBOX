<?php
    $this->assets->add_css_file('fuelux/dist/css/fuelux.min.css', DOCUMENT_HEAD);
    $this->assets->add_js_file('fuelux/dist/js/fuelux.min.js', DOCUMENT_BODY_END);
?>
<div class="repeater" id="myRepeater">
    <div class="repeater-header">
      <div class="repeater-header-left">
        <span class="repeater-title">Awesome Repeater</span>
        <div class="repeater-search">
          <div class="search input-group">
            <input type="search" class="form-control" placeholder="Search"/>
            <span class="input-group-btn">
              <button class="btn btn-default" type="button">
                <span class="glyphicon glyphicon-search"></span>
                <span class="sr-only">Search</span>
              </button>
            </span>
          </div>
        </div>
      </div>
      <div class="repeater-header-right">
        <div class="btn-group selectlist repeater-filters" data-resize="auto">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            <span class="selected-label">&nbsp;</span>
            <span class="caret"></span>
            <span class="sr-only">Toggle Filters</span>
          </button>
          <ul class="dropdown-menu" role="menu">
            <li data-value="all" data-selected="true"><a href="#">all</a></li>
            <li data-value="some"><a href="#">some</a></li>
            <li data-value="others"><a href="#">others</a></li>
          </ul>
          <input class="hidden hidden-field" name="filterSelection" readonly="readonly" aria-hidden="true" type="text"/>
        </div>
        <div class="btn-group repeater-views" data-toggle="buttons">
          <label class="btn btn-default active">
            <input name="repeaterViews" type="radio" value="list"><span class="glyphicon glyphicon-list"></span>
          </label>
          <label class="btn btn-default">
            <input name="repeaterViews" type="radio" value="thumbnail"><span class="glyphicon glyphicon-th"></span>
          </label>
        </div>
      </div>
    </div>
    <div class="repeater-viewport">
      <div class="repeater-canvas"></div>
      <div class="loader repeater-loader"></div>
    </div>
    <div class="repeater-footer">
      <div class="repeater-footer-left">
        <div class="repeater-itemization">
          <span><span class="repeater-start"></span> - <span class="repeater-end"></span> of <span class="repeater-count"></span> items</span>
          <div class="btn-group selectlist" data-resize="auto">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              <span class="selected-label">&nbsp;</span>
              <span class="caret"></span>
              <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu" role="menu">
              <li data-value="5"><a href="#">5</a></li>
              <li data-value="10" data-selected="true"><a href="#">10</a></li>
              <li data-value="20"><a href="#">20</a></li>
              <li data-value="50" data-foo="bar" data-fizz="buzz"><a href="#">50</a></li>
              <li data-value="100"><a href="#">100</a></li>
            </ul>
            <input class="hidden hidden-field" name="itemsPerPage" readonly="readonly" aria-hidden="true" type="text"/>
          </div>
          <span>Per Page</span>
        </div>
      </div>
      <div class="repeater-footer-right">
        <div class="repeater-pagination">
          <button type="button" class="btn btn-default btn-sm repeater-prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <span class="sr-only">Previous Page</span>
          </button>
          <label class="page-label" id="myPageLabel">Page</label>
          <div class="repeater-primaryPaging active">
            <div class="input-group input-append dropdown combobox">
              <input type="text" class="form-control" aria-labelledby="myPageLabel">
              <div class="input-group-btn">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                  <span class="caret"></span>
                  <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right"></ul>
              </div>
            </div>
          </div>
          <input type="text" class="form-control repeater-secondaryPaging" aria-labelledby="myPageLabel">
          <span>of <span class="repeater-pages"></span></span>
          <button type="button" class="btn btn-default btn-sm repeater-next">
            <span class="glyphicon glyphicon-chevron-right"></span>
            <span class="sr-only">Next Page</span>
          </button>
        </div>
      </div>
    </div>
  </div>
<?php $this->js_start(); ?>
    var dataSource = function(options, callback){
        var items = filtering(options);
        var resp = {
            count: items.length,
            items: [],
            page: options.pageIndex,
            pages: Math.ceil(items.length/(options.pageSize || 50))
        };
        var i, items, l;

        i = options.pageIndex * (options.pageSize || 50);
        l = i + (options.pageSize || 50);
        l = (l <= resp.count) ? l : resp.count;
        resp.start = i + 1;
        resp.end = l;

        if(options.view==='list' || options.view==='thumbnail'){
            if(options.view==='list'){
                resp.columns = columns;
                for(i; i<l; i++){
                    resp.items.push(items[i]);
                }
            }else{
                for(i; i<l; i++){
                    resp.items.push({
                        color: colors[items[i].type.split(', ')[0]],
                        name: items[i].name,
                        src: items[i].ThumbnailImage
                    });
                }
            }

            setTimeout(function(){
                callback(resp);
            }, delays[Math.floor(Math.random() * 4)]);
        }
    };

    var filtering = function(items, options){
        var search;
        if(options.filter.value!=='all'){
            items = _.filter(items, function(item){
                return (item.type.search(options.filter.value)>=0);
            });
        }
        if(options.search){
            search = options.search.toLowerCase();
            items = _.filter(items, function(item){
                return (
                    (item.name.toLowerCase().search(options.search.toLowerCase())>=0) ||
                    (item.id.toLowerCase().search(options.search.toLowerCase())>=0) ||
                    (item.type.toLowerCase().search(options.search.toLowerCase())>=0) ||
                    (item.height.toLowerCase().search(options.search.toLowerCase())>=0) ||
                    (item.weight.toLowerCase().search(options.search.toLowerCase())>=0) ||
                    (item.abilities.toLowerCase().search(options.search.toLowerCase())>=0) ||
                    (item.weakness.toLowerCase().search(options.search.toLowerCase())>=0)
                );
            });
        }
        if(options.sortProperty){
            items = _.sortBy(items, function(item){
                if(options.sortProperty==='id' || options.sortProperty==='height' || options.sortProperty==='weight'){
                    return parseFloat(item[options.sortProperty]);
                }else{
                    return item[options.sortProperty];
                }
            });
            if(options.sortDirection==='desc'){
                items.reverse();
            }
        }

        return items;
    };

    // REPEATER
    $('#repeaterIllustration').repeater({
        dataSource: dataSource
    });
    $('table.table').repeater({dataSource: dataSource});
<?php $this->js_end(DOCUMENT_BODY_END); ?>
