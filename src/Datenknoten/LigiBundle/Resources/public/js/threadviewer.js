$(document).ready(function(){
    $('.ui.feed .date').each(function(){
        var $item = $(this);
        var date = new moment($item.text().trim());
        var new_content = $('<abbr>')
            .text(date.fromNow())
            .attr('title',date.format('YYYY-MM-D HH:mm'));
        $item.html(new_content[0].outerHTML);
    });
});
