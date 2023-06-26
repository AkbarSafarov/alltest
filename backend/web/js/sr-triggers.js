$(function() {
    
    let el, sentData;
    
    //      Ajax function
    
    function ajaxSend(event, el, url, method = 'get', sentData = {}, callback = false) {
        event.preventDefault();
        
        let ajaxExtraParams = {},
            insertType = el.data('sr-insert-type');
        
        if (sentData instanceof FormData) {
            ajaxExtraParams = {
                processData: false,
                contentType: false
            };
        }
        
        $('body').addClass('loading');
        
        $.ajax({...ajaxExtraParams, ...{
            url: url,
            type: method,
            data: sentData,
            
            xhr: function() {
                let xhr = new window.XMLHttpRequest();
                
                if (el.data('sr-show-progress')) {
                    xhr.upload.addEventListener('progress', function(evt) {
                        if (evt.lengthComputable) {
                            let percentComplete = (evt.loaded / evt.total) * 100;
                            
                            $('#upload-progress-bar').removeClass('d-none');
                            $('#upload-progress-bar .progress-bar').width(percentComplete + '%');
                            $('#upload-progress-bar .progress-bar').html(Math.round(percentComplete) + '%');
                        }
                    }, false);
                }
                
                return xhr;
            },
            
            beforeSend: function() {
                $('#upload-progress-bar .progress-bar').width('0%');
            },
            
            success: function(data) {
                $('body').removeClass('loading');
                $('#upload-progress-bar').addClass('d-none');
                
                //      Setting data to wrapper
                
                switch (typeof(el.data('sr-wrapper'))) {
                    case 'string':
                        if (insertType === undefined) {
                            $(el.data('sr-wrapper')).html(data);
                        } else {
                            eval("$(el.data('sr-wrapper'))." + insertType + "(data)");
                        }
                        break;
                        
                    case 'object':
                        for (key in el.data('sr-wrapper')) {
                            $(el.data('sr-wrapper')[key]).html(data[key]);
                        }
                        break;
                }
                
                //      Callbacks init
                
                if (callback !== false) {
                    callback(data);
                }
                
                if (el.data('sr-callback') !== undefined) {
                    eval(el.data('sr-callback'));
                }
            }
        }});
    }
    
    //      Ajax button
    
    $(document).on('click', '[data-sr-trigger*="ajax-button"]', function(event) {
        el = $(this);
        ajaxSend(event, el, el.data('sr-url'), el.data('sr-method'));
    });
    
    //      Ajax form
    
    $(document).on('submit', '[data-sr-trigger*="ajax-form"]', function(event) {
        el = $(this);
        
        switch (el.attr('method')) {
            case 'get':
                sentData = el.serialize();
                break;
            case 'post':
                sentData = new FormData(el[0]);
                break;
        }
        
        ajaxSend(event, el, el.attr('action'), el.attr('method'), sentData);
    });
    
    //      Ajax change
    
    $(document).on('change', '[data-sr-trigger*="ajax-change"]', function(event) {
        el = $(this);
        ajaxSend(event, el, el.data('sr-url'), el.data('sr-method'), {value: el.val()});
    });
    
    //      Ajax keypress
    
    $(document).on('keyup', '[data-sr-trigger*="ajax-keypress"]', function(event) {
        el = $(this);
        
        if (el.val().length >= el.data('sr-min')) {
            ajaxSend(event, el, el.data('sr-url'), el.data('sr-method'), {value: el.val()});
        }
    });
});
