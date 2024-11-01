jQuery(function ($) {

  $('.detail-wrapper').readmore();

  $('#country').change(function () {

    var data1 = {
      // action: 'ajaxRegionAction',
      country_id: this.value
    };
    jQuery.ajax({
      url: ajaxURL,
      data: data1,
      dataType: 'json',
      method: 'GET',
      type: 'GET',
      success: function (response) {
        console.log(response);
        jQuery('#region').html('');
        jQuery.each(response.result, function (index, val) {
          jQuery('#region').append('<option value="' + val.id + '">' + val.name + '</option>');
        });
        // console.log((response));
      },
    });
  });

  // accepts number only for radius textfield
  $("#mile-radius").keydown(function (event) {
    // Allow only backspace and delete
    if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 190) {
      // let it happen, don't do anything
    } else {
      // Ensure that it is a number and stop the keypress
      if (event.keyCode < 48 || event.keyCode > 57) {
        event.preventDefault();
      }
    }
  });

  //show loader
  $('.btn').click(function () {
    $('#loader').fadeIn();
  });


  //toggle apply with cv only

  $('#cv-toggle-option').click(function () {
    var $this = $(this);

    if ($this.is(':checked')) {
      $('#cv-only').css('display', 'block');
      $('#cv-and-form').css('display', 'none');
    } else {
      $('#cv-only').css('display', 'none');
      $('#cv-and-form').css('display', 'block');
    }
  });
}(jQuery));
