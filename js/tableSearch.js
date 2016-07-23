$("#matches-table-search").keyup(function () {
    var txtVal = $(this).val();
    if (txtVal != "") {
        $(".matches-table").show();
        $(".message").remove();
        $.each($('.matches-table'), function (i, o) {
            var match = $("td:contains-ci('" + txtVal + "')", this);
            match.parent().siblings().hide();
            if (match.length > 0) $(match).parent("tr").show();
            else $(this).hide();
        });
    } else {
        // When there is no input or clean again, show everything back
        $("tbody > tr", this).show();
    }
    if($('.matches-table:visible').length == 0)
    {
        $('#matches-table-search').after('<p class="message">Sorry No results found!!!</p>');
    }
});

$('#matches-table-search').click(function() {
    $(this).addClass('focused'); 
});

// jQuery expression for case-insensitive filter
$.extend($.expr[":"], {
    "contains-ci": function (elem, i, match, array) {
        return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
    }
});