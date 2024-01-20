jQuery(document).ready(function ($) {

    // Select & deselect all checkboxes
    $('#select-all').on('click', function () {
      const isChecked = $(this).hasClass('allChecked');
      $('input[type="checkbox"]', '#wcbloat-multi-checkbox-table').prop('checked', !isChecked);
      $(this).toggleClass('allChecked');
    });
  
    // Confirm leaving the plugin settings page without saving changes
    let needToConfirm = false;
    $(window).on('beforeunload', function () {
      if (needToConfirm) {
        return 'Your unsaved data will be lost.';
      }
    });
  
    $('input').on('change', function () {
      needToConfirm = true;
    });

    $('a#select-all').on('click', function () {
      needToConfirm = true;
    });
  
    $('#submit').on('click', function () {
      needToConfirm = false;
    });

    // Do not confirm while importing a json file
    $('input#wcbloat-upload-json-file').on('click', function () {
      needToConfirm = false;
    });
  
    // Show & hide element when a radio input is selected
    const $adminDisableRadio = $('input[type=radio][name=wcbloat_admin_disable]');
    const $adminDisableFeatures = $('tr.wcbloat_admin_disable_features');
    
    function toggleAdminDisableFeatures() {
      $adminDisableFeatures.toggle($adminDisableRadio.filter(':checked').val() === 'disable-wc-admin-features');
    }
    
    toggleAdminDisableFeatures();
    $adminDisableRadio.on('change', toggleAdminDisableFeatures);

    // Fix for Plugin data screen - import / export
    
    var currentUrl = window.location.href;

    var pattern = "page=disable-bloat&tab=data";
    if (currentUrl.includes(pattern)) {
        $('input[type="submit"]').last().hide();
    }
  
  });
  