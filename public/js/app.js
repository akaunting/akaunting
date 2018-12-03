$(document).ready(function () {
    // Live Search
    $.fn.liveSearch = function (option) {
        return this.each(function () {
            this.timer = null;
            this.items = new Array();

            $.extend(this, option);

            $(this).attr('autocomplete', 'off');

            // Blur
            $(this).on('blur', function () {
                setTimeout(function (object) {
                    object.hide();
                }, 200, this);
            });

            // Keydown
            $(this).on('input', function (event) {
                this.request();
            });

            // Show
            this.show = function () {
                var pos = $('#live-search').position();

                $(this).parent().parent().siblings('ul.dropdown-menu').css({
                    top : pos.top + $('#live-search').height(),
                    width: $('#live-search').width(),
                    left: pos.left
                });

                $(this).parent().parent().siblings('ul.dropdown-menu').show();
            };

            // Hide
            this.hide = function () {
                $(this).parent().parent().siblings('ul.dropdown-menu').hide();
            };

            // Request
            this.request = function () {
                clearTimeout(this.timer);

                this.timer = setTimeout(function (object) {
                    object.source($(object).val(), $.proxy(object.response, object));
                }, 200, this);
            };

            // Response
            this.response = function (json) {
                html = '';

                if (json.length) {
                    for (i = 0; i < json.length; i++) {
                        this.items[json[i]['id']] = json[i];
                    }

                    var count = json.length;

                    for (i = 0; i < count; i++) {
                        html += '<li data-value="' + json[i]['type'] + '-' + json[i]['id'] + '">';
                        html += '   <a href="' + json[i]['href'] + '">';
                        html += '       <div class="live-search">';
                        html += '           <div class="row">';
                        html += '               <div class="name" style="color: ' + json[i]['color'] + ';">' + json[i]['name'] + '</div>';
                        html += '               <span class="type">' + json[i]['type'] + '</span>';
                        html += '           </div>';
                        html += '       </div>';
                        html += '   </a>';
                        html += '</li>';
                    }
                }

                if (html) {
                    this.show();
                } else {
                    this.hide();
                }

                $(this).parent().parent().siblings('ul.dropdown-menu').html(html);
            };

            $(this).parent().parent().after('<ul class="dropdown-menu" id="result-search" style="padding:2px 2px 2px 2px;"></ul>');
            $(this).parent().parent().siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));
        });
    };

    $('#live-search input[name=\'live-search\']').liveSearch({
        'source': function (request, response) {
            if (request != '' && request.length > 2) {
                $.ajax({
                    url     : url_search,
                    type    : 'GET',
                    dataType: 'JSON',
                    data    : 'keyword=' + $(this).val(),
                    success : function (json) {
                        response($.map(json, function (item) {
                            return {
                                id   : item.id,
                                name : item.name,
                                type : item.type,
                                color: item.color,
                                href : item.href
                            }
                        }));
                    }
                });
            } else {
                $('#live-search > .dropdown-menu').hide();
            }
        }
    });

    last_radio = '';

    $('input:radio').each(function () {
        if ($(this).parent().parent().hasClass('radio-inline')) {
            input_name    = $(this).attr("name");
            input_value   = $(this).attr("value");
            input_text    = $(this).text();
            input_checked = $(this).is(':checked');

            enable_class  = 'btn-default';
            disable_class = 'btn-default';

            if (last_radio == input_name) {
                return;
            }

            last_radio = input_name;

            if ($(':radio[name="' + input_name + '"]').length != 2) {
                return;
            }

            if ((input_value != 0) && (input_value != 1)) {
                return;
            }

            if ((input_text.localeCompare(text_yes) == 1) && (input_text.localeCompare(text_no) == 1)) {
                return;
            }

            if (input_value == 1 && input_checked == true) {
                enable_class = 'btn-success active';
            } else {
                disable_class = 'btn-danger active';
            }

            $('#' + input_name + '_1').removeClass('btn-default').addClass(enable_class);
            $('#' + input_name + '_0').removeClass('btn-default').addClass(disable_class);
        }
    });

    $(document).on('click', '.btn-group label:not(.active)', function (e) {
        disable_label = $(this);
        disable_input = $('#' + disable_label.attr('id') + ' :input');

        if (disable_input.attr('type') != 'radio') {
            return;
        }

        if (!disable_input.is(':checked')) {
            enable_input = $('input[name="' + disable_input.attr('name') + '"]:checked');
            enable_label = enable_input.parent();

            enable_label.removeClass('btn-success active');
            enable_label.removeClass('btn-danger active');

            enable_input.removeAttr('checked');
            enable_label.addClass('btn btn-default');

            disable_label.removeClass('btn-default');

            if (disable_input.val() == 0) {
                disable_label.addClass('btn-danger active');
            } else {
                disable_label.addClass('btn-success active');
            }

            disable_input.attr('checked', 'checked');

            enable_input.trigger('change');
            disable_input.trigger('change');
        }
    });

    if (document.getElementById('recurring_frequency')) {
        $('.input-group-recurring #recurring_frequency').select2();
        $('.input-group-recurring #recurring_frequency').trigger('change');
    }

    $('.form-loading-button').submit(function( event ) {
        $('.button-submit').button('loading');

        return true;
    });

    if (document.getElementsByClassName('input-group-invoice-text').length) {
        $('.input-group-invoice-text select').select2();
    }
});

$(document).on('click', '.popup', function(e) {
    e.preventDefault();

    $('#modal-popup').remove();

    var element = this;

    $.ajax({
        url: $(element).attr('href'),
        type: 'get',
        dataType: 'html',
        success: function(data) {
            html  = '<div class="modal" id="modal-popup">';
            html += '  <div class="modal-dialog">';
            html += '    <div class="modal-content">';
            html += '      <div class="modal-header">';
            html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
            html += '        <h4 class="modal-title">' + $(element).text() + '</h4>';
            html += '      </div>';
            html += '      <div class="modal-body">' + data + '</div>';
            html += '    </div';
            html += '  </div>';
            html += '</div>';

            $('body').append(html);

            $('#modal-popup').modal('show');
        }
    });
});

$(document).on('change', '.input-group-recurring #recurring_frequency', function (e) {
    var value = $(this).val();

    var recurring_frequency = $('#recurring_frequency').parent().parent();
    var recurring_interval = $('#recurring_interval').parent();
    var recurring_custom_frequency = $('#recurring_custom_frequency').parent();
    var recurring_count = $('#recurring_count').parent();

    if (value == 'custom') {
        recurring_frequency.removeClass('col-md-12').removeClass('col-md-12').addClass('col-md-4');

        recurring_interval.removeClass('hidden');
        recurring_custom_frequency.removeClass('hidden');
        recurring_count.removeClass('hidden');

        $('#recurring_custom_frequency').select2();
    } else if (value == 'no' || value == '') {
        recurring_frequency.removeClass('col-md-10').removeClass('col-md-4').addClass('col-md-12');

        recurring_interval.addClass('hidden');
        recurring_custom_frequency.addClass('hidden');
        recurring_count.addClass('hidden');
    } else {
        recurring_frequency.removeClass('col-md-12').removeClass('col-md-4').addClass('col-md-10');

        recurring_interval.addClass('hidden');
        recurring_custom_frequency.addClass('hidden');
        recurring_count.removeClass('hidden');
    }
});

$(document).on('change', '.input-group-invoice-text select', function () {
    var invoice_text_custom = $(this).parent().parent().parent().find('input');

    if ($(this).val() == 'custom') {
        $(this).parent().parent().removeClass('col-md-12').addClass('col-md-6');

        invoice_text_custom.parent().removeClass('hidden');

        $(this).select2();
    } else {
        $(this).parent().parent().removeClass('col-md-6').addClass('col-md-12');

        invoice_text_custom.parent().addClass('hidden');
    }
});

function confirmDelete(form_id, title, message, button_cancel, button_delete) {
    $('#confirm-modal').remove();

    var html  = '';

    html += '<div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">';
    html += '  <div class="modal-dialog">';
    html += '      <div class="modal-content">';
    html += '          <div class="modal-header">';
    html += '              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
    html += '              <h4 class="modal-title" id="confirmModalLabel">' + title + '</h4>';
    html += '          </div>';
    html += '          <div class="modal-body">';
    html += '              <p>' + message + '</p>';
    html += '              <p></p>';
    html += '          </div>';
    html += '          <div class="modal-footer">';
    html += '              <div class="pull-left">';
    html += '                  <button type="button" class="btn btn-danger" onclick="$(\'' + form_id + '\').submit();">' + button_delete + '</button>';
    html += '                  <button type="button" class="btn btn-default" data-dismiss="modal">' + button_cancel + '</button>';
    html += '              </div>';
    html += '          </div>';
    html += '      </div>';
    html += '  </div>';
    html += '</div>';

    $('body').append(html);

    $('#confirm-modal').modal('show');
}

function convertDateFormat(date, split_character) {
    var result = [];
    var formats = {
        'd': 'DD',
        'M': 'MMM',
        'Y': 'YYYY',
        'F': 'MMMM',
        'm': 'MM'
    };

    dates = date.split(split_character);

    dates.forEach(function(value) {
        result.push(formats[value]);
    });

    return result.join(split_character);
}

function itemTableResize() {
    colspan = $('#items.table.table-bordered thead tr th').length - 1;

    $('#items.table.table-bordered tbody #addItem .text-right').attr('colspan', colspan);
    $('#items.table.table-bordered tbody #tr-subtotal .text-right:first').attr('colspan', colspan);
    $('#items.table.table-bordered tbody #tr-discount .text-right:first').attr('colspan', colspan);
    $('#items.table.table-bordered tbody #tr-tax .text-right:first').attr('colspan', colspan);
    $('#items.table.table-bordered tbody #tr-total .text-right:first').attr('colspan', colspan);
}

function notificationHide(path, id, token) {
    $.ajax({
        url: app_url + '/common/notifications/disable',
        type: 'POST',
        dataType: 'JSON',
        data: {path: path, id: id},
        headers: { 'X-CSRF-TOKEN': token },
        success: function(json) {
            if (json['success']) {
                $('#notification-' + id).remove();
            }
        }
    });
}
