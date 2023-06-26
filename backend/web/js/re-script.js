$(function() {
    let el, action, sendData,
        csrf = $('meta[name="csrf-token"]').attr('content');
    
    //      Common init
    
    $('body').attr('data-sidebar-size', $('meta[name="sidebar-size"]').attr('content'));
    $('#side-menu .active:not(.menuitem-active)').addClass('menuitem-active').find('.collapse').collapse('toggle');
    
    //      Bootstrap collapse toggle
    
    $(document).on('click', '#side-menu [data-bs-toggle="collapse"]', function(event) {
        event.preventDefault();
        
        $(this).closest('ul').children('li').removeClass('active menuitem-active');
        
        if (!$(this).next().hasClass('show')) {
            $(this).closest('li').addClass('active menuitem-active');
        }
        
        $(this).next().collapse('toggle');
    });
    
    //      Datepickers
    
    $(document).on('mousedown', '[data-toggle="datepicker"], input[name*="Search[created_at]"], input[name*="Search[updated_at]"]', function() {
        if (!$(this).hasClass('flatpickr-input')) {
            $(this).flatpickr({
                dateFormat: 'd.m.Y',
                locale: {
                    firstDayOfWeek: 1
                }
            });
        }
    });
    
    $(document).on('mousedown', '[data-toggle="datetimepicker"]', function() {
        if (!$(this).hasClass('flatpickr-input')) {
            $(this).flatpickr({
                dateFormat: 'd.m.Y H:i',
                enableTime: true,
                time_24hr: true,
                locale: {
                    firstDayOfWeek: 1
                }
            });
        }
    });
    
    $(document).on('mousedown', '[data-toggle="timepicker"]', function() {
        if (!$(this).hasClass('flatpickr-input')) {
            $(this).flatpickr({
                dateFormat: 'H:i',
                noCalendar: true,
                enableTime: true,
                time_24hr: true
            });
        }
    });
    
    //      Select2
    
    $.fn.select2.amd.define('select2/selectAllAdapter', [
        'select2/utils',
        'select2/dropdown',
        'select2/dropdown/attachBody'
    ], function (Utils, Dropdown, AttachBody) {
        function SelectAll() { }
        SelectAll.prototype.render = function (decorated) {
            var self = this,
                $rendered = decorated.call(this),
                $selectAll = $(
                    '<span class="s2-togall-button s2-togall-select"><span class="s2-select-label">Выбрать все</span></span>'
                ),
                $btnContainer = $('<div style="margin-top:3px;">').append($selectAll);
            if (!this.$element.prop("multiple")) {
                // this isn't a multi-select -> don't add the buttons!
                return $rendered;
            }
            $rendered.find('.select2-dropdown').prepend($btnContainer);
            $selectAll.on('click', function (e) {
                $(self.$element).find('option:not([value=""])').prop('selected', 'selected').closest('select').trigger('change');
                self.trigger('change.select2');
                self.trigger('close');
            });
            return $rendered;
        };
        
        return Utils.Decorate(
            Utils.Decorate(
                Dropdown,
                AttachBody
            ),
            SelectAll
        );
    });
    
    $(document).on('mousedown', '[data-toggle="select2"]:not([multiple])', function() {
        if (!$(this).hasClass('select2-hidden-accessible')) {
            $(this).select2({
                allowClear: true,
                placeholder: ''
            }).select2('open');
        }
    });
    
    $(document).on('mousedown', '[data-toggle="select2"][multiple]', function() {
        if (!$(this).hasClass('select2-hidden-accessible')) {
            $(this).select2({
                allowClear: true,
                placeholder: '',
                dropdownAdapter: $.fn.select2.amd.require('select2/selectAllAdapter')
            }).select2('open');
        }
    });
    
    $(document).on('mousedown', '[data-toggle="select2-ajax"]', function() {
        action = $(this).data('action');
        
        if (!$(this).hasClass('select2-hidden-accessible')) {
            $(this).select2({
                allowClear: true,
                placeholder: ' ',
                ajax: {
                    url: action,
                    dataType: 'json',
                    delay: 300,
                    data: function (params) { return {q: params.term}; }
                }
            }).select2('open');
        }
    });
    
    //      ElFinder
    
    let elfinderId, elfinderParams, elfinderFilter,
        elfinderUrl = $('meta[name="elfinder-connection-url"]').attr('content');
    
    $(document).on('click', '[data-toggle="elfinder"] .yii2-elfinder-select-button', function() {
        if ($(this).hasClass('elfinder-initialized')) {
            return false;
        }
        
        elfinderId = $(this).closest('.elfinder-container').find('.yii2-elfinder-input').attr('id');
        elfinderParams = 'width=' + window.outerWidth + ',height=' + window.outerHeight;
        elfinderFilter = $(this).closest('[data-toggle="elfinder"]').data('filter');
        elfinderFilter = elfinderFilter === undefined ? '' : elfinderFilter;
        
        alexantr.elFinder.registerSelectButton($(this).attr('id'), elfinderUrl + '?id=' + elfinderId);
        window.open(elfinderUrl + '?id=' + elfinderId + '&filter=' + elfinderFilter, 'elfinder-select-file').focus();
    });
    
    $(document).on('click', '.btn-elfinder-remove', function() {
        $(this).closest('.elfinder-container').find('img, audio, video').remove();
        $(this).closest('.elfinder-container').find('input').removeAttr('value');
    });
    
    //      Redactor
    
    let RedactorBaseUrl = $('meta[name="tinymce-base-url"]').attr('content'),
        RedactorFilePickerUrl = $('meta[name="tinymce-file-picker-connection-url"]').attr('content'),
        RedactorImageUploadUrl = $('meta[name="tinymce-image-upload-connection-url"]').attr('content'),
        RedactorParams = JSON.parse($('meta[name="tinymce-params"]').attr('content'));
    
    alexantr.tinyMceWidget.setBaseUrl(RedactorBaseUrl);
    
    $(document).on('click', '[data-toggle="redactor"]', function() {
        if ($(this).prev('.mce-tinymce').length === 0) {
            alexantr.tinyMceWidget.register($(this).attr('id'), RedactorParams);
        }
    });
    
    $(document).on('click', '[data-toggle="redactor-modal"]', function() {
        $('#redactor-modal').modal('show');
        
        if ($('#redactor-modal-input').prev('.mce-tinymce').length === 0) {
            alexantr.tinyMceWidget.register('redactor-modal-input', RedactorParams);
        }
        
        setTimeout(() => {
            tinymce.get('redactor-modal-input').setContent($(this).val());
            tinymce.get('redactor-modal-input').selection.select(tinymce.get('redactor-modal-input').getBody(), true);
            tinymce.get('redactor-modal-input').selection.collapse(false);
            tinymce.get('redactor-modal-input').focus();
        }, 100);
        
        $('#redactor-modal-button').data('el', $(this));
    });
    
    $(document).on('click', '#redactor-modal-button', function() {
        $(this).data('el').val(tinymce.get('redactor-modal-input').getContent());
        $(this).data('el').focus();
        $('#redactor-modal').modal('hide');
    });
    
    //      Sortable
    
    $(document).on('mouseenter', '[data-toggle="sortable"]', function() {
        if (!$(this).hasClass('ui-sortable')) {
            $(this).sortable({
                handle: '.table-sorter',
                placeholder: 'sortable-placeholder',
                start: function(event, ui) {
                    ui.placeholder.height(ui.helper.outerHeight());
                }
            });
        }
    });
    
    //      Form autocomplete switching off
    
    $(document).on('focus', 'input, textarea, select', function() {
        $(this).attr('autocomplete', 'off');
    });
    
    //      Tests validation
    
    let testsValidationTable,
        testsValidationText = $('meta[name="tests-validation-text"]').attr('content');
    
    $(document).on('submit', '#update-form', function() {
        testsValidationTable = $(this).find('.tests-validation-table');
        
        for (i = 0; i < testsValidationTable.length; i++) {
            if (
                testsValidationTable.eq(i).find('input[type="radio"][name*="[options][answers][]"]:checked').length === 0 &&
                testsValidationTable.eq(i).find('input[type="checkbox"][name*="[options][answers][]"]:checked').length === 0
            ) {
                alert(testsValidationText);
                $(window).scrollTop(testsValidationTable.eq(i).offset().top - 70);
                return false;
            }
        }
        
        return true;
    });
    
    //      Reset button
    
    let form;
    
    $(document).on('click', '[type="reset"]', function(event) {
        event.preventDefault();
        
        if ($(this).attr('form') !== undefined) {
            form = $('#' + $(this).attr('form'));
        } else {
            form = $(this).closest('form');
        }
        
        form.find('input[type="text"], textarea, select').val('').closest('form').trigger('submit');
    });
    
    //      Popover & tooltip
    
    $('[data-toggle="popover"]').popover({placement: 'top', trigger: 'hover'});
    $('[data-toggle="tooltip"]').not('td .action-buttons [data-toggle="tooltip"]').tooltip();
    $('td .action-buttons [data-toggle="tooltip"]').tooltip({placement: 'right'});
    
    //      Toast
    
    let alertJson,
        alertLoaderColors = {
            success: '#80CFB1',
            warning: '#F5C480',
            danger: '#F5A480',
            info: '#80D1D5'
        };
    
    if ($('#main-alert').html().trim() !== '[]') {
        alertsJson = JSON.parse($('#main-alert').html());
        
        for (key in alertsJson) {
            for (alert_key in alertsJson[key]) {
                $.toast({
                    heading: alertsJson[key][alert_key],
                    position: 'bottom-right',
                    loaderBg: alertLoaderColors[key],
                    icon: key,
                    hideAfter: 4000,
                    stack: 10
                });
            }
        }
    }
    
    //      GridView common button
    
    $(document).on('change', '[name="selection[]"]', function() {
        $('.common-buttons').addClass('d-none');
        
        $('[name="selection[]"]').each(function() {
            if ($(this).prop('checked')) {
                $('.common-buttons').removeClass('d-none');
            }
        });
    });
    
    //      Table relations
    
    let rand,
        relHtml, relHtmlTmp = [];
    
    $('.table-new-relation').each(function() {
        relHtmlTmp[$(this).data('table')] = $('<div/>').append($(this).clone().removeClass('table-new-relation')).html();
        $(this).remove();
    });
    
    $(document).on('ajaxComplete', function() {
        $('.table-new-relation').each(function() {
            relHtmlTmp[$(this).data('table')] = $('<div/>').append($(this).clone().removeClass('table-new-relation')).html();
            $(this).remove();
        });
        
        passwordHash = '';
        $('#password-form input[name="password"]').val('');
    });
    
    $(document).on('click', '.table-relations > tfoot .btn-add', function() {
        el = $(this);
        rand = Date.now();
        
        relHtml = relHtmlTmp[el.data('table')].replace(/\__key__/g, rand);
        el.closest('table').find('tbody').append(relHtml).find('[data-toggle="redactor"]').click();
    });
    
    $(document).on('click', '.table-relations .btn-remove', function() {
        $(this).closest('tr').remove();
    });
    
    //      Delete confirmation
    
    let attribute, passwordHash = '',
        url, urlParams;
    
    $(document).on('click', '[data-trigger*="delete-confirmation-button"]', function(event) {
        el = $(this);
        attribute = el.prop('tagName') === 'A' ? 'href' : 'formaction';
        
        if (passwordHash.length === 0) {
            event.preventDefault();
            
            $('#password-modal').modal('show');
            $('#password-modal input[name="password"]').focus();
            $('#password-form').data('el', el);
        } else {
            if (attribute === 'href') {
                $.post(el.attr(attribute), {
                    "_csrf-backend": csrf
                });
            }
        }
    });
    
    $(document).on('deleteRecord', '[data-trigger*="delete-confirmation-form"]', function() {
        el = $(this).data('el');
        passwordHash = $(this).data('password-hash');
        
        if (passwordHash.length === 0) {
            $('#password-form input[name="password"]').val('').focus();
        } else {
            url = new URL(location.origin + el.attr(attribute));
            urlParams = new URLSearchParams(url.search);
            urlParams.append('password', passwordHash);
            
            el.attr(attribute, url.pathname + '?' + urlParams.toString());
            
            if (attribute === 'href') {
                el.trigger('click');
            } else {
                el.closest('form').attr('action', el.attr(attribute)).trigger('submit');
            }
        }
    });
    
    //      Toggle session
    
    $(document).on('click', '[data-sr-trigger*="toggle_session"]', function(event) {
        event.preventDefault();
        
        el = $(this);
        url = el.data('sr-url');
        sendData = {
            "_csrf-backend": csrf,
            name: el.data('sr-name'),
            value: el.data('sr-value')
        };
        
        $.post(url, sendData, function(data) {
            if (el.data('sr-callback') !== undefined) {
                eval(el.data('sr-callback'));
            }
        });
    });
});
