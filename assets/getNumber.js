$(function(){
    // Set up the number formatting.
    $('.getNumber').on('change',function(){
        console.log('Change event.');
        var val = $('.getNumber').val();

    });
    $('.getNumber').number( true, 0 );

});
$(function(){
    // Set up the number formatting.
    $('.getNumeric').on('change',function(){
        console.log('Change event.');
        var val = $('.getNumeric').val();

    });
    $('.getNumeric').number( true, 2 );

});
$(function(){
    // Set up the number formatting.
    $('.getInt').on('change',function(){
        console.log('Change event.');
        var val = $('.getNumeric').val();

    });
    $('.getInt').number( true, 0 );

});
