// Javascript for popups
$(document).ready(function(){
    // When the button is clicked, show the popup
    $(".record-details").click(function(){
      var index = $(".record-details").index($(this));
       $(".record-popup").eq(index).show();
    });

    // When the close button is clicked, hide the popup
    $(".close-popup").click(function(){
       $(this).closest(".record-popup").hide();
    });
 });

$(document).ready(function() {
   $('#datetime').on('keydown', function(event) {
      if(event.key === 'Enter') {
         event.preventDefault;
         $(this).closest('form').submit();
      }
   });
});

