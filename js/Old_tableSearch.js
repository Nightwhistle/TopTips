$(document).ready(function() {
    var $rows = $('.matches-table tbody tr');
    $('#matches-table-search').keyup(function() {
        var val = '^(?=.*\\b' + $.trim($(this).val()).split(/\s+/).join('\\b)(?=.*\\b') + ').*$',
            reg = RegExp(val, 'i'),
            text;

        $rows.show().filter(function() {
            text = $(this).text().replace(/\s+/g, ' ');
            return !reg.test(text);
        }).hide();
    });
    
    $('#matches-table-search').click(function() {
        $(this).addClass('focused'); 
    });
    
});

