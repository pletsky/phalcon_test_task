

function messageDelete(id) {

    var url = "/index/ajax_delete/"+id; // the script where you handle the form input.

    $.ajax({
        type: "POST",
        url: url,
        data: '{"id":'+id+'}',
        success: function(data) {
            var obj = jQuery.parseJSON(data);
            if (obj.res) {
                var last_row = false;
                if ($('[id^="msg"]:first').attr('id') == $('[id^="msg"]:last').attr('id')) {
                    last_row = true;
                }
                $("#msg"+id).remove();
                if (last_row) {
                    window.location = '/';
                }
//                alert(obj.txt);
            }
        }
    });
}


function messageAdd() {

  $("#message_add_form").submit(function(e) {

    var url = "/index/ajax_create/"; // the script where you handle the form input.

    $.ajax({
        type: "POST",
        url: url,
        data: $("#message_add_form").serialize(), // serializes the forms elements.
        success: function(data) {
//alert(data);
            try {
                var obj = jQuery.parseJSON(data);
                if (obj.res == false) {
                    alert(obj.msg);
                    return;
                }
            } catch (err) {
                $('[id^="msg"]:first').before(data);
                $("#message_add_form")[0].reset();
            }
        }
    });

    e.preventDefault(); // avoid to execute the actual submit of the form.

  });
}


