<?php
    require_once('lib/globals.php');

    $random_order_number = randomNumber(2) . "-" . randomNumber(4);
    $random_event_id = "S" . strtoupper(randomLetters(1)) . randomNumber(4);
    $ticket_date = strtoupper(getTicketDate());
    $random_zip_code = "ZIP" . randomNumber(4);
    $random_cc_code = "VI " . rand(1, 399) . "X";

    function randomLetters($num_chars) {
        $letters = "";
        for($i=0; $i<$num_chars; $i++) {
            $letters .= chr(64+rand(0,26));
        }

        return $letters;
    }

    function randomNumber($digits) {
        return rand(pow(10, $digits-1), pow(10, $digits)-1);
    }

    function getTicketDate() {
        return date('dMy');
    }
?>

<?php
include 'header.php';
?>

<style>
    .sm-field {
        width: 50px;
    }

    .ticket-info-tbl td {
        min-width: 120px;
    }

    @media (max-width: 768px) {
        .ticket-info-tbl td {
            display: block;
        }
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-sm-12 main">

<h1 class="page-header" style="margin-bottom: 0;">Generate One</h1>
<form class="form-vertical" role="form" method="POST" action="#">

<div class="well well-lg">
    <table class="table ticket-info-tbl">
        <tr>
            <td>NAME:</td>
            <td style="width: 250px;"><input id="customer_name" value="Sam Cohen"
                style="width: 100%" autofocus="autofocus" /></td>
            <td style="padding-left: 30px;">SECTION:</td>
            <td><input class="sm-field" id="section" value="314" /></td>
            <td>ROW:</td>
            <td><input class="sm-field" id="row" value="2" /></td>
            <td>SEAT:</td>
            <td><input class="sm-field" id="seat" value="5" /></td>
        </tr>
        <tr>
            <td>ORDER NUMBER:</td>
            <td><input id="order_number" style="width: 100%" value="<?php echo $random_order_number; ?>" /></td>

            <td>BARCODE:</td>
            <td colspan="5"><input id="barcode" style="width: 100%" value="" /></td>
        </tr>

        <tr>
            <td>ZIP_CODE:</td>
            <td><input id="zip_code" style="width: 100%" value="<?php echo $random_zip_code; ?>" /></td>

            <td>CREDIT CARD:</td>
            <td colspan="5"><input id="cc_code" style="width: 100%" value="<?php echo $random_cc_code; ?>" /></td>
        </tr>

        <tr>
            <td>EVENT ID:</td>
            <td ><input id="event_id" style="width: 100%" value="<?php echo $random_event_id; ?>" /></td>

            <td>EVENT DATE:</td>
            <td colspan="3"><input id="event_date" style="width: 100%" value="wed june 14 2017" /></td>

            <td>TIME:</td>
            <td><input id="event_time" style="width: 100%" value="6:00PM" /></td>
        </tr>

        <tr>
            <td>VENUE:</td>
            <td><input id="venue" style="width: 100%" value="ALAMODOME SA/TX" /></td>

            <td>Ticket Date:</td>
            <td colspan="3"><input id="ticket_date" style="width: 100%" value="<?php echo $ticket_date; ?>" /></td>
            <td>SEAT NOTES:</td>
            <td><input style="width: 100%" id="seat_notes" value="UPPER LEVEL" /></td>
        </tr>
        <tr>
            <td>EVENT NAME:</td>
            <td>
                <textarea id="event_name" rows="3" style="width: 100%;">99.5 KISS-FM PRESENTS
METALLICA
WWW.METALLICA.COM</textarea>
            </td>

            <td>Notes:</td>
            <td colspan="4">
                <textarea id="notes" rows="3" style="width: 100%;">DOORS OPEN AT 4:00PM</textarea>
            </td>
        </tr>
    </table>
</div>

<div id="ticket-preview" class="panel panel-default" style="display: block;">
  <div class="panel-body">
    <?php include 'tm-ticket-template.php'; ?>
  </div>
</div>

<div class="well well-sm" style="text-align: center;">
    <a class="btn btn-success generate-btn" style="width: 120px;">Generate</a>
</div>

</form>

                </div>
            </div>
        </div>
    </div>

<!-- Scripts -->
<script src="assets/js/app.js"></script>
<script>
    function bindField(input_id, display_class) {
        $('#' + input_id).on('keyup mouseup', function (event, previousText) {
            var value = $(this).val().toUpperCase();
            value = value.replace(/\r\n|\r|\n/g,"<br />");

            $('.' + display_class).html(value);
            updatePdfComment();
        });
    }

    function updatePdfComment() {
        var barcode_val = $('#barcode').val();
        var section = $('#section').val();
        var row = $('#row').val();
        var seat = $('#seat').val();

        var comment_str = 'PDFCOMMENT{"source":"ticketfire","section":"' +
            section +'","row":"' + row + '","seat":"' + seat +
            '","barcode":"' + barcode_val + '"}';

        $('#pdf_comment').text(comment_str);
    }

    bindField('customer_name',  'customer_name_display');
    bindField('section',        'section_display');
    bindField('row',            'row_display');
    bindField('seat',           'seat_display');
    bindField('seat_notes',     'seat_notes_display')

    bindField('order_number',   'order_number_display');
    bindField('ticket_date',    'ticket_date_display');
    bindField('cc_code',        'cc_code_display');
    bindField('zip_code',        'zip_code_display');

    bindField('event_name',     'event_name_display');
    bindField('event_id',       'event_id_display');

    bindField('event_date',     'event_date_display');
    bindField('event_time',     'event_time_display');
    bindField('venue',          'venue_display');
    bindField('notes',          'notes_display');

    $('#barcode').on('change', function (event, previousText) {
        var barcode_val = $('#barcode').val();
        var data = {
            barcode: barcode_val
        };

        $.post('lib/generate-barcode.php', data, function(response){

            var new_src = "data:image/png;base64, " + response;

            $('#barcode_img').attr('src', new_src);
            $('#barcode_img2').attr('src', new_src);

            var barcode_string = barcode_val.toString();
            var barcode_val_spaced = "";
            for(var i=0; i<barcode_string.length; i++) {
                if (i && (i % 4 === 0)) {
                    barcode_val_spaced += ' ';
                }
                barcode_val_spaced += barcode_string[i];
            }

            $('.barcode_display').html(barcode_val_spaced);
            updatePdfComment();
        });
    });

    $('.generate-btn').click(function(){
        var html_content = $('#ticket-preview .panel-body').html();
        var data = {
          event_id: $('#event_id').val(),
          event_name: $('#event_name').val(),
          section: $('#section').val(),
          row: $('#row').val(),
          seat: $('#seat').val(),
          pdf_content: html_content
        };

        updatePdfComment();

        $(this).attr("disabled",true);
        $(this).html("Working...");

        /*var w = window.open();
        $(w.document.body).html(html_content);*/
        $.post('lib/submit-generate-one.php', data, function(response){
            window.open("lib/view-tm-ticket.php?filename=" + response);

            $('.generate-btn').html("Generate");
            $('.generate-btn').attr("disabled", false);
        });
    });
</script>

<?php
include 'footer.php';
?>