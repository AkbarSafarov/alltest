$(function() {
    
    let el, action, sendData;
    
    //      Checking alert modal
    
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
    
    //      Lazy load button
    
    let offset, offsetMax;
    
    $(document).on('click', '[data-toggle="lazy-load-button"]', function(e) {
        e.preventDefault();
        
        el = $(this);
        action = el.data('action');
        sendData = {offset: el.data('offset')};
        offsetMax = parseInt(el.data('offset_max'));
        
        $.get(action, sendData, function(data) {
            $(el.data('result')).append(data);
            
            offset = parseInt(el.data('offset'));
            offset < offsetMax ? el.data('offset', offset + 1) : el.remove();
        });
    });
    
    //      Cart changing form
    
    $(document).on('submit', '[data-toggle="cart-change-form"]', function(e) {
        e.preventDefault();
        
        el = $(this);
        action = el.attr('action');
        sendData = el.serialize();
        
        $.post(action, sendData, function(data) {
            el.find('.btn-with-text').text(data.change_type);
            
            $.toast({
                heading: data.change_alert,
                position: 'bottom-right',
                loaderBg: alertLoaderColors['success'],
                icon: 'success',
                hideAfter: 4000,
                stack: 10
            });
            
            $('#cart-quantity').html(data.quantity);
            $('#cart-page').html(data.page);
            
            if (data.quantity > 0) {
                $('#cart-quantity').addClass('_active');
            } else {
                $('#cart-quantity').removeClass('_active');
            }
        });
    });
});
