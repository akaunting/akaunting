!function ($) {

    "use strict"; // jshint ;_;

    /* FANCYFILE CLASS DEFINITION
  * ========================= */

    var fancyfile = '[data-toggle=fancyfile]'
    , FancyFile = function (element, options) {
        var $el = $(element);
        this.options = $.extend({}, $.fn.fancyfile.defaults, options);
        this.makeFancy($el);
    };

    FancyFile.prototype = {
        constructor: FancyFile,
        makeFancy : function (element) {
            var $fancy = $(this.options.container);
            var $clone = element.clone( true );
            var classes = $clone.attr('class');
            var $aLink = $(this.options.fakeButton);
            var $fakeInput = $(this.options.fakeInput);
            var colorized = false;
            if(classes) {
                classes.split(' ').forEach(function(bc){
                    if(/input|span/i.test(bc)) {
                        $fancy.find('div').addClass(bc);
                    }
                    if(/primary|info|success|warning|danger|inverse/.test(bc)) {
                        colorized = true;
                        $aLink.addClass(bc);
                    }
                });
            }
            var buttonText = element.attr('data-text');
            if(buttonText) {
                this.options.text = buttonText === 'false' ? '' : buttonText;
            }
            var buttonIcon = element.attr('data-icon');
            if(buttonIcon) {
                this.options.icon = buttonIcon === 'false' ? '' : '<i class="' + buttonIcon + '"></i>';
            }
            var buttonStyle = element.attr('data-style');
            if(buttonStyle) {
                colorized = buttonStyle === 'false' ? false : true ;
                this.options.style = buttonStyle === 'false' ? '' : buttonStyle;
            }
            var icon = this.options.icon ? this.options.icon + ' ' : '';
            $aLink.html(icon + this.options.text);
            if(colorized || /primary|info|success|warning|danger|inverse/.test(this.options.style)) {
                $aLink.find('i').addClass('icon-white');
            }
            if(this.options.style !== '') {
                $aLink.addClass(this.options.style);
            }
            var buttonPlaceholder = element.attr('data-placeholder');
            if(buttonPlaceholder) {
                this.options.placeholder = buttonPlaceholder === 'false' ? '' : buttonPlaceholder;
            }
            $fakeInput.attr('placeholder', this.options.placeholder);
            $fancy.insertBefore(element);
            $clone.attr('type','file');
            $fancy.append($clone);
            $fancy.find('div').append($fakeInput).append($aLink);
            var nW = $fancy.find('div').width();
            $clone.width(nW);
            element.remove();
            $fakeInput.width((nW - $aLink.width()) - 41).height(20);
            $aLink.height(20);

            $clone.hover(function(e){
                $(this).parent().find('.fake-input').addClass('active');
                $(this).parent().find('.btn').addClass('active');
            }, function(e){
                $(this).parent().find('.fake-input').removeClass('active');
                $(this).parent().find('.btn').removeClass('active');
            });
            $clone.on('change.fancyfile.data-api', FancyFile.prototype.change);
        },
        change: function (e) {
            var file = this.files[0],
            name = file.name;
            $(this).parent().find('.fake-input').val(name);
        }
    };

    /* FANCYFILE PLUGIN DEFINITION
   * ========================== */

    var old = $.fn.fancyfile;

    $.fn.fancyfile = function (options) {
        return this.each(function () {
            var $this = $(this)
            , data = $this.data('fancyfile');
            if (!data) $this.data('fancyfile', (data = new FancyFile(this, options)));
            if (typeof options === 'string') data[options].call($this);
        });
    };

    $.fn.fancyfile.defaults = {
        container   : '<div class="fancy-file"><div class="fake-file"></div></div>',
        fakeInput   : '<input class="fake-input form-control" type="text" />',
        fakeButton  : '<button class="btn"></button>',
        text        : 'Select File',
        icon        : '<i class="icon-file glyphicon glyphicon-file"></i>',
        style       : '',
        placeholder : 'Select File…'
    };

    $.fn.fancyfile.Constructor = FancyFile;

    /* FANCYFILE NO CONFLICT
   * ==================== */

    $.fn.fancyfile.noConflict = function () {
        $.fn.fancyfile = old;
        return this;
    };

    /* DATA-API APPLY TO STANDARD FANCYFILE ELEMENTS
   * ============== */

    $(window).on('load', function () {
        $(fancyfile).each(function () {
            $(this).fancyfile();
        });
    });

}(window.jQuery);