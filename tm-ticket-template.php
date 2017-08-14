<style>
    .barcode-wrap {
        position: absolute;
        text-align: center;
        font-family: arial;
        font-size: 8pt;
        font-weight: bold;
        color: black;
    }

    .barcode-wrap img {
        border-top: 3px solid #000;
        border-bottom: 3px solid #000;
    }

    .rotate-90 {
        -webkit-transform: rotate(-90deg);
        -moz-transform: rotate(-90deg);
        -ms-transform: rotate(-90deg);
        -o-transform: rotate(-90deg);
        transform: rotate(-90deg);
        -webkit-transform-origin: top right;
        -moz-transform-origin: top right;
        -o-transform-origin: top right;
        -ms-transform-origin: top right;
        transform-origin: top right;
    }
    .tix-label {
        font-family: arial;
        font-size: 6.8pt;
        font-weight: bold;
        color: #E21B22;
        white-space: nowrap;
    }

    .tix-text {
        font-family: Courier;
        font-weight: bold;
        font-size: 7.8pt;
        color: #000000;
        text-transform: uppercase;
    }

    .tix-column {
        height: 116px;
    }

    .instructions-ticket-info {
        font-family: Courier;
        font-size: 6.5pt;
    }

    .customer_name_display {
        overflow: hidden;
        max-width: 175px;
        text-align: left;
    }

    .seat_notes_display,
    .venue_display,
    .notes_display {
        max-width: 230px;
        white-space: nowrap;
        overflow: hidden;
    }

    .event_name_display {
        max-width: 230px;
        height: 44px;
        white-space: pre-wrap;
        overflow: hidden;
    }

    .event_datetime_display {
        position: absolute;
        bottom: 2px;
        width: 230px;
        overflow: hidden;
        white-space: nowrap;
    }
</style>

<div style="position: relative; width: 612px; margin: 0 auto;">

    <img src='https://s3.amazonaws.com/tinkerbell-assets/tm-ticket-template-300p.png' style="width: 612px;"/>

    <div style="left: 52px; top: 60px;
        position: absolute; min-width: 440px; max-width: 460px;">
        <div class="tix-label" style="float: left;">ISSUED TO</div>
        <div class="tix-text customer_name_display" style="float: left; margin-left: 12px;">[NAME]</div>

        <div class="tix-text seat_display" style="float: right; margin-top: 2px;">[SEAT]</div>
        <div class="tix-label" style="float: right; margin-right: 12px;">SEAT</div>

        <div class="tix-text row_display" style="float: right; margin-right: 12px;
        margin-top: 2px;">[ROW]</div>
        <div class="tix-label" style="float: right;margin-right: 12px;">ROW</div>

        <div class="tix-text section_display" style="float: right;
            margin-right: 14px; margin-top: 2px;">[SECT]</div>
        <div class="tix-label" style="float: right; margin-right: 12px;">SECTION</div>
    </div>

    <div style="left: 52px; top: 80px; position: absolute;">
        <div class="tix-label" style="float: left;">ORDER NUMBER</div>
        <div class="tix-text order_number_display" style="float: left; margin-left: 5px;">[ORDER]</div>
    </div>

    <!-- bottom left -->
    <div class="tix-text tix-column" style="text-align: right; position: absolute;
        right: 474px; top: 120px; line-height: 1.4;">
            <span class="event_id_display">[EVENT_ID]</span><br/>
            <br/>
            <br/>
            <span class="section_display">[SECT]</span><br/>
            <span class="cc_code_display">[CC_CODE]</span><br/>
            <span class="row_display">[ROW]</span>
            &nbsp;&nbsp;
            <span class="seat_display">[SEAT]</span><br/>
            <span class="zip_code_display">[ZIP_CODE]</span><br/>
            <span class="ticket_date_display">[TICKET_DATE]</span>
    </div>

    <!-- bottom mid col -->
    <div class="tix-text tix-column" style="text-align: left; position: absolute;
        left: 152px; top: 120px; line-height: 1.4;">
            <div style="max-width: 230px;">
                <span class="section_display">[SECT]</span>
                <span class="row_display">[ROW]</span>
                <span class="seat_display">[SEAT]</span>
            </div>
            <div class="tix-text seat_notes_display">[SEAT_NOTES]</div>
            <div class="tix-text event_name_display">[EVENT_NAME]</div>
            <div class="tix-text venue_display">[VENUE]</div>
            <div class="tix-text notes_display">[NOTES]</div>
            <div class="tix-text event_datetime_display">
                <span class="event_date_display">[DATE]</span>
                <span class="event_time_display">[TIME]</span>
            </div>
    </div>

    <!-- bottom right col -->
    <div class="tix-text tix-column" style="text-align: left; position: absolute;
        left: 410px; top: 120px; line-height: 1.4;">
            E<span class="event_id_display">[EVENT_ID]</span><br/>
            <br/>
            CN 42132<br/>
            <span class="section_display">[SECT]</span><br/>
            <br/>
            <span class="row_display">[ROW]</span><br/>
            <br/>
            <span class="seat_display">[SEAT]</span>
    </div>

    <div id="barcode-wrap1" class="barcode-wrap rotate-90"
        style="right: 78px; top: 60px; width: 175px;">

        <img id="barcode_img" style="max-width: 100%; height: 30px;" alt="barcode" src="[BARCODE_SRC]" />
            <br/>
        <span class="barcode_display">[BARCODE]</span>
    </div>

    <div style="position: absolute;
        right: 7px; top: 80px;">

        <img src="https://s3.amazonaws.com/tinkerbell-assets/tm-vertical-copyright.png" style="width: 12px;">
    </div>
</div>

<table style="width: 612px; margin: 10px auto; position: relative;">
    <tr>
        <td style="padding-right: 10px; width: 50%;">
            <img src="https://s3.amazonaws.com/tinkerbell-assets/tm-promo-verified-tickets.png" style="width: 100%;">
        </td>
        <td style="padding-left: 10px; width: 50%;">
            <img src="https://s3.amazonaws.com/tinkerbell-assets/tm-promo-ticket-deals.png" style="width: 100%;">
        </td>
    </tr>
    <tr>
        <td valign="top" style="padding-right: 10px; padding-top: 10px;">
            <div style="position: relative;">
                <img src="https://s3.amazonaws.com/tinkerbell-assets/tm-disclaimer-block.png" style="width: 100%;">
                <br/>

                <img src="https://s3.amazonaws.com/tinkerbell-assets/tm-thankyou-ticketfast.png" style="width: 100%; margin-top: 5px;">

                <div id="pdf_comment" style="color: #fff; position: absolute; width: 612px; text-align: left; overflow-wrap: break-word;">
                </div>
            </div>
        </td>
        <td valign="top" style="padding-left: 10px; padding-top: 10px;">
            <img src="https://s3.amazonaws.com/tinkerbell-assets/tm-promo-2for1.png" style="width: 100%;"><br/>

            <img src="https://s3.amazonaws.com/tinkerbell-assets/tm-important-instructions.png"
                style="width: 100%; margin-top: 5px;">

            <!-- bottom instructions barcode -->
            <div style="width: 100%; position: relative;">
                <div style="position: absolute; left: 5px; top: -120px;; width: 99%;">

                    <table class="instructions-ticket-info" style="width: 100%;">
                        <tr>
                            <td align="left">E<span class="event_id_display">[EVENT_ID]</span></td>
                            <td>Section: <span class="section_display">[SECT]</span></td>
                            <td>Row: <span class="row_display">[ROW]</span></td>
                            <td style="padding-right: 10px" align="right">Seat: <span class="seat_display">[SEAT]</span></td>
                        </tr>
                    </table>
                </div>

                <div class="barcode-wrap"
                    style="top: -87px; width: 296px;">

                    <img id="barcode_img2" style="margin: 0 auto; max-width: 200px;" alt="barcode" src="[BARCODE_SRC]" />
                        <br/>
                    <span class="barcode_display">[BARCODE]</span>
                </div>
            </div>
        </td>
    </tr>
</table>
