<?php
require_once('lib/globals.php');

include 'header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-sm-12 main">

            <h1 class="page-header" style="margin-bottom: 0;">Generate Multiple</h1>
            <form id="import_form" class="form-vertical" role="form" method="POST" action="lib/submit-import-csv.php"
              enctype="multipart/form-data">

                <div class="box">
                    <input type="file" name="csv_file" id="csv_file" class="inputfile inputfile-1" data-multiple-caption="{count} files selected" multiple />
                    <label for="csv_file"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Choose a file&hellip;</span></label>
                </div>
          </form>
        </div>
    </div>
</div>
<script>
'use strict';

;( function ( document, window, index )
{
    var inputs = document.querySelectorAll( '.inputfile' );
    Array.prototype.forEach.call( inputs, function( input )
    {
        var label    = input.nextElementSibling,
            labelVal = label.innerHTML;

        input.addEventListener( 'change', function( e )
        {
            var fileName = '';
            if( this.files && this.files.length > 1 )
                fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
            else
                fileName = e.target.value.split( '\\' ).pop();

            if( fileName )
                label.querySelector( 'span' ).innerHTML = fileName;
            else
                label.innerHTML = labelVal;
        });

        // Firefox bug fix
        input.addEventListener( 'focus', function(){ input.classList.add( 'has-focus' ); });
        input.addEventListener( 'blur', function(){ input.classList.remove( 'has-focus' ); });
    });

    $(".inputfile").change(function (){
       $( "#import_form" ).submit();
    });
}( document, window, 0 ));
</script>

<?php
include 'footer.php';
?>