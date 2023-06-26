<script>
    document.addEventListener('DOMContentLoaded', function() {
        let id, action, sendData,
            oldIndex, newIndex;
        
        action = '<?= Yii::$app->urlManager->createUrl($route) ?>';
        
        $('.grid-view tbody').sortable({
            handle: '.table-sorter',
            placeholder: 'sortable-placeholder',
            start: function(event, ui) {
                ui.placeholder.height(ui.helper.outerHeight());
                id = ui.item[0].dataset.id;
                oldIndex = ui.item.index();
            },
            stop: function(event, ui) {
                newIndex = ui.item.index();
                
                if (oldIndex !== newIndex) {
                    sendData = {
                        "_csrf-backend": $('meta[name=csrf-token]').attr('content'),
                        id: id,
                        old_index: oldIndex,
                        new_index: newIndex
                    };
                    
                    $.post(action, sendData);
                }
            }
        }).disableSelection();
    });
</script>