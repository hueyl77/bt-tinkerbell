<?php
include 'header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-sm-12 main">
            <style>
                #data_rows tr:hover {
                    cursor: pointer;
                }

                #data_rows tr.selected {
                    background-color: #d8f5e9;
                }
                </style>

                <h1 class="page-header" style="margin-bottom: 0;">Generate Multiple</h1>
                <form class="form-vertical" role="form" method="POST" action="/tinkerbell/submit-generate-multiple" onsubmit="formSubmitted()">

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

                        <!-- for loop here -->
                            <tr class="selected">
                                <td>event_id</td>
                                <td>event_name</td>
                                <td>event_date event_time</td>
                                <td>event_venue</td>
                                <td>firstname lastname</td>
                                <td>section</td>
                                <td>row</td>
                                <td>seat</td>
                                <td>barcode</td>
                                <td>
                                    <input type="checkbox" name="row_checkboxes[]" class="row_checkboxes" style="visibility: hidden;" value="index_key" checked="checked" />
                                </td>
                            </tr>
                        <!-- end for loop -->
                        </table>
                    </div>

                    <div class="well well-sm" style="text-align: center;">
                        <button type="submit" id="btn-generate" class="btn btn-success" style="width: 120px;">Generate</button>
                    </div>

                    <input type="hidden" name="filepath" value="/filepath" />
                </form>
        </div>
    </div>
</div>

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