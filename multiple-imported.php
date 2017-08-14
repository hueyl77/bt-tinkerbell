<?php
require_once('lib/globals.php');

$csv_file = _getVar($_REQUEST, 'csv-file');
if (!$csv_file) {
    die("Invalid csv_file");
}

$csv_filepath = storage_path("csvs/") . $csv_file;

$data_rows = [];

if (file_exists($csv_filepath)) {
    $row = 1;
    $indexes = [];

    if (($handle = fopen($csv_filepath, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);
            if ($row == 1) {
                for ($c=0; $c < $num; $c++) {
                    $indexes[$data[$c]] = $c;
                }
                $row++; continue; // skip first line (labels)
            }

            array_push($data_rows, [
                "event_id"      => $data[$indexes['event_id']],
                "event_name"    => $data[$indexes['event_name']],
                "venue"         => $data[$indexes['venue']],
                "date"          => $data[$indexes['event_date']],
                "time"          => $data[$indexes['event_time']],
                "section"       => $data[$indexes['section']],
                "row"           => $data[$indexes['row']],
                "seat"          => $data[$indexes['seat']],
                "seat_notes"    => $data[$indexes['seat_notes']],
                "barcode"       => $data[$indexes['barcode']],
                "fullname"      => $data[$indexes['fullname']],
                "order_number"  => $data[$indexes['order_number']],
                "ticket_date"   => $data[$indexes['ticket_date']]
            ]);

            $row++;
        }
        fclose($handle);
    }
}
?>

<?php
include 'header.php';
?>

<style>
#data_rows tr:hover {
    cursor: pointer;
}

#data_rows tr.selected {
    background-color: #d8f5e9;
}
</style>
<div class="container">
    <div class="row">
        <div class="col-sm-12 main">

<h1 class="page-header" style="margin-bottom: 0;">Generate Multiple</h1>
<form class="form-vertical" role="form" method="POST" action="lib/submit-generate-multiple.php" onsubmit="formSubmitted()">
    <div>
        <div style="text-align: right; font-size: 16px; cursor: pointer;">
            <input type="checkbox" id="check_all" checked="checked">
            <label for="check_all">Select / Deselect All</label>
        </div>
        <table id="data_rows" class="table table-striped table-hover">
        <tr>
            <th>event_id</th>
            <th>event_name</th>
            <th>date time</th>
            <th>venue</th>
            <th>fullname</th>
            <th>section</th>
            <th>row</th>
            <th>seat</th>
            <th>barcode</th>
            <th></th>
        </tr>

        <?php foreach ($data_rows as $index_key => $row): ?>
            <tr class="selected">
                <td><?php echo $row['event_id']; ?></td>
                <td><?php echo $row['event_name']; ?></td>
                <td><?php echo $row['date'] . " " . $row['time']; ?></td>
                <td><?php echo $row['venue']; ?></td>
                <td><?php echo $row['fullname']; ?></td>
                <td><?php echo $row['section']; ?></td>
                <td><?php echo $row['row']; ?></td>
                <td><?php echo $row['seat']; ?></td>
                <td><?php echo $row['barcode']; ?></td>
                <td>
                    <input type="checkbox" name="row_checkboxes[]" class="row_checkboxes" style="visibility: hidden;" value="<?php echo $index_key+1; ?>" checked="checked" />
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
    </div>

    <div class="well well-sm" style="text-align: center;">
        <button type="submit" id="btn-generate" class="btn btn-success" style="width: 120px;">Generate</button>
    </div>

    <input type="hidden" name="csv-file" value="<?php echo $csv_file; ?>" />
</form>

<script>
    $('#data_rows tr').click(function(){
        var checkbox = $(this).find('.row_checkboxes')
        checkbox.prop("checked", !checkbox.prop("checked"));

        if (checkbox.prop("checked")) {
            $(this).addClass('selected');
        } else {
            $(this).removeClass('selected');
        }
    });

    $("#check_all").change(function(){
        $('.row_checkboxes').each(function(){
            $(this).prop("checked", $("#check_all").prop("checked"));
            if ($("#check_all").prop("checked")) {
                $(this).parents("tr").addClass('selected');
            } else {
                $(this).parents("tr").removeClass('selected');
            }
        })
    });

    function formSubmitted() {
        $("#btn-generate").attr("disabled",true);
        $("#btn-generate").html("Working...");
    }
</script>

<?php
include 'footer.php';
?>
