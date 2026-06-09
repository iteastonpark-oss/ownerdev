<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 10/2/2016
 * Time: 10:22 AM
 */
?>

<div class="btn-group">
    <a href="javascript:select(1)" class="btn btn-xs btn-primary">
        <i class="fa fa-check-square-o"></i> Pilih</a>
    <a href="javascript:select(0)" class="btn btn-xs btn-warning">
        <i class="fa fa-square-o"></i> Uncheck</a>

</div>


<script>
    function select(a) {
        var theForm = document.myForm;
       for (var i = 0; i < theForm.elements.length; i++) {
       // for (var i = 0; i < 22 ; i++) {
            if (theForm.elements[i].name == 'check[]')
                theForm.elements[i].checked = a;
        }
    }

</script>