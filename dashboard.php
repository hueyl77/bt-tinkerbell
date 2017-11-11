<?php
    require_once('lib/globals.php');

    $random_order_number = generateOrderNumber();
    $random_event_id = "S" . strtoupper(randomLetters(1)) . randomNumber(4);
    $ticket_date = getTicketDate();
    $random_zip_code = generateZipCode();
    $random_cc_code = generateCcCode();

    $cc_chars = substr($random_cc_code, 0, 2);
    $random_cc_zip = generateCcZip($cc_chars);

    $storage_url = getenv('STORAGE_URL');
    $max_num_seats = 9;
?>

<?php
include 'header.php';
?>

<style>
    .sm-field {
        width: 50px;
    }

    .highlighted-field {
        border: 2px dashed red;
    }

    .ticket-info-tbl label,
    .ticket-info-tbl  input {
        margin-right: 12px;
    }

    .add-seat-icon {
        cursor: pointer;
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
<form name="ticketform" class="form-vertical" role="form" method="POST" action="lib/dashboard-multiple-submit.php">

<div class="well well-lg" style="position: relative;">
    <table class="table ticket-info-tbl">
        <tr>
            <td>
                <label>NAME:</label>
                <input id="customer_name" name="fullname" autofocus="autofocus" />
            </td>
            <td>
                <label>ORDER NUMBER:</label>
                <input id="order_number" value="<?php echo $random_order_number; ?>" name="order_number" />
            </td>
        </tr>


        <!-- seats / barcodes rows -->
        <?php for($i=1; $i<=$max_num_seats;$i++):?>
        <tr id="more-seats-row<?php echo $i;?>" <?php if ($i > 1): ?> style="display: none;" <?php endif;?>>

            <?php if ($i == 1): ?>
                <td>
                    <label>SECTION:</label>
                    <input class="sm-field" id="section" name="section" />
                    <label>ROW:</label>
                    <input class="sm-field" id="row" name="row" />
                </td>
            <?php else: ?>
                <td></td>
            <?php endif; ?>
            <td>
                <label>SEAT <?php echo $i;?>:</label>
                <input class="sm-field" id="seat<?php echo $i;?>" name="seat<?php echo $i;?>" />
                <label>BARCODE <?php echo $i;?>:</label>
                <input id="barcode<?php echo $i;?>" name="barcode<?php echo $i;?>" value="" />
                <?php if ($i <= $max_num_seats - 1): ?>
                    <i class="fa fa-plus add-seat-icon"></i>
                <?php endif; ?>
            </td>
        </tr>
        <?php endfor;?>
        <!-- end seats / barcodes rows -->

        <tr>
            <td>
                <label>ZIP_CODE:</label>
                <input id="zip_code" name="zip_code" value="<?php echo $random_zip_code; ?>" />
            </td>
            <td>
                <label>CREDIT CARD:</label>
                <input id="cc_code" name="cc_code" value="<?php echo $random_cc_code; ?>" />

                <label>CC_ZIP:</label>
                <input id="cc_zip" name="cc_zip" value="<?php echo $random_cc_zip; ?>" />
            </td>
        </tr>

        <tr>
            <td>
                <label>EVENT ID:</label>
                <input id="event_id" name="event_id"
                value="<?php echo $random_event_id; ?>" />
            </td>

            <td>
                <label>EVENT DATE:</label>
                <input id="event_date" name="event_date" value="wed june 14 2017" />
                <label>TIME:</label>
                <input id="event_time" name="event_time" value="6:00PM" />
            </td>
        </tr>

        <tr>
            <td>
                <label>VENUE:
                <input id="venue" name="venue" />
            </td>
            <td>
                <label>Ticket Date:</label>
                <input id="ticket_date" name="ticket_date" value="<?php echo $ticket_date; ?>" />
                <label>SEAT NOTES:</label>
                <input id="seat_notes" name="seat_notes" />
            </td>
        </tr>
        <tr>
            <td>
                <label>EVENT NAME:</label><br/>
                <textarea id="event_name" name="event_name"
                    rows="3" style="width: 100%;"></textarea>
            </td>

            <td>
                <label>Notes:</label><br/>
                <textarea id="notes" name="notes"
                    rows="3" style="width: 100%;"></textarea>
            </td>
        </tr>
    </table>

    <div style="text-align: center;">
        <a class="btn btn-success generate-btn" style="width: 120px;">Generate</a>
    </div>
    <div style="position: absolute; bottom: 3px; right: 10px;">
        [<a href="javascript:exportCsv()">Export CSV Sample</a>]
    </div>
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

        $('#' + input_id).on('focus', function (event) {
            $('.' + display_class).addClass('highlighted-field');
        });

        $('#' + input_id).on('blur', function (event) {
            $('.highlighted-field').removeClass('highlighted-field');
        });
    }

    function updatePdfComment() {
        var barcode_val = $('#barcode1').val();
        var section = $('#section').val();
        var row = $('#row').val();
        var seat = $('#seat1').val();

        var comment_str = 'PDFCOMMENT{"source":"ticketfire","section":"' +
            section +'","row":"' + row + '","seat":"' + seat +
            '","barcode":"' + barcode_val + '"}';

        $('#pdf_comment').text(comment_str);
    }

    bindField('customer_name',  'customer_name_display');
    bindField('section',        'section_display');
    bindField('row',            'row_display');
    bindField('seat1',          'seat_display');
    bindField('seat_notes',     'seat_notes_display')

    bindField('order_number',   'order_number_display');
    bindField('ticket_date',    'ticket_date_display');
    bindField('cc_code',        'cc_code_display');
    bindField('zip_code',       'zip_code_display');
    bindField('cc_zip',         'cc_zip_display');

    bindField('event_name',     'event_name_display');
    bindField('event_id',       'event_id_display');

    bindField('event_date',     'event_date_display');
    bindField('event_time',     'event_time_display');
    bindField('venue',          'venue_display');
    bindField('notes',          'notes_display');

    $('#barcode1').on('change', function (event, previousText) {
        var barcode_val = $('#barcode1').val();
        var data = {
            barcode: barcode_val
        };

        $.post('lib/generate-barcode.php', data, function(response){

            var new_src = "<?php echo $storage_url ?>barcodes/" + barcode_val + ".jpg";

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
        var form = $('form[name="ticketform"]');
        form.attr('action', 'lib/submit-dashboard-multiple.php');
        form.submit();
    });

    var current_seat_rows = 1;
    $('.add-seat-icon').click(function() {
        current_seat_rows++;
        if (current_seat_rows <= <?php echo $max_num_seats;?>) {
            $('.add-seat-icon').hide();
            $('#more-seats-row' + current_seat_rows).show();
            $('#more-seats-row' + current_seat_rows).find('.add-seat-icon').show();
        }
    });

    function exportCsv() {
        var form = $('form[name="ticketform"]');
        form.attr('target', '_blank');
        form.attr('action', 'lib/submit-export-csv.php');
        form.submit();
    }
</script>

<?php
include 'footer.php';
?>
