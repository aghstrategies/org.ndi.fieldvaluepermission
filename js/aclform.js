CRM.$(function ($) {
  //Moves custom field div to above buttons
  $('#id-custom-field-id').insertAfter('#id-event-acl');

  // function to show and hide the custom field info div
  var showCustomFieldInputs = function () {
    var $objectType = $("input[name='object_type']:checked").val();
    if ($objectType == 100) {
      $('#id-custom-field-id').show();
      $('#id-group-acl').hide();
      $('#id-profile-acl').hide();
      $('#id-custom-acl').hide();
      $('#id-event-acl').hide();
    } else {
      $('input#custom_field_id').val('').trigger('change');
      $('input#custom_field_value').val('').trigger('change');
      $('#id-custom-field-id').hide();
    }
  };

  showCustomFieldInputs();
  $("input[name='object_type']").change(showCustomFieldInputs);

  // TODO deal with default value
  // var vid = CRM.vars.LogVolHours.vid;
  // if (vid > 0) {
  //   $('#volunteer_project_select').val(vid).trigger('change');
  // }
  var setUpValueInput = function () {
    var $customFieldId = $('#custom_field_id').val();

    // $customFieldId = 13;
    if ($customFieldId > 0) {
      CRM.api3('CustomField', 'getSingle', {
        sequential: 1,
        id: $customFieldId,
      }).done(function (result) {
        console.log(result);
        if (result.option_group_id) {
          $('#custom_field_value').crmEntityRef({
            entity: 'OptionValue',
            api: {
              params: { option_group_id: result.option_group_id },
              label_field: 'label',
            },
            select: {
              minimumInputLength: 0,
              placeholder: '- Select a Custom Field Value -',
            },
          });
        } else {
          $('#custom_field_value').select2('destroy');
        }
      });
    }
  };

  setUpValueInput();
  $('#custom_field_id').change(setUpValueInput);

});
