/*
Template: Datum - Responsive Bootstrap 4 Admin Dashboard Template
Author: iqonic.design
Design and Developed by: iqonic.design
NOTE: This file contains the styling for responsive Template.
*/

/*----------------------------------------------
Index Of Script
------------------------------------------------

:: Tooltip
:: Fixed Nav
:: Sidebar Widget
:: Page Loader
:: Close  navbar Toggle
:: user toggle
:: Data tables
:: Form Validation
:: Flatpicker
:: Scrollbar
:: checkout
:: Datatables
:: SVG Animation
------------------------------------------------
Index Of Script
----------------------------------------------*/
(function(jQuery) {

    "use strict";

    $(document).on('change', '.datatable-filter [data-filter="select"]', function() {
      window.renderedDataTable.ajax.reload(null, false)
    })
  
    $(document).on('input', '.dt-search', function() {
      window.renderedDataTable.ajax.reload(null, false)
    })

    // confirm message box
    const confirmSwal = async (message) => {
        return await Swal.fire({
          title: message,
          showCancelButton: true,
          confirmButtonText: 'Yes',
          cancelButtonText:'No'
        }).then((result) => {
          return result
        })
      }
    
      window.confirmSwal = confirmSwal
    
      $('#quick-action-form').on('submit', function(e) {
        e.preventDefault()
        const form = $(this)
        const url = form.attr('action')
        console.log(url)
        const message = form.find('button[data-ajax="true"]').data('message');
        const rowdIds = $("#datatable_wrapper .select-table-row:checked").map(function() {
            return $(this).val();
        }).get();
        
    
        confirmSwal(message).then((result) => {
            if(!result.isConfirmed) return
            callActionAjax({url: `${url}?rowIds=${rowdIds}`,body: form.serialize()})
          })
      
      })

  $(document).on('change', '#datatable_wrapper .switch-status-change', function() {
    let url = $(this).attr('data-url')
    let body = {
      status: $(this).prop('checked') ? 1 : 0,
      _token: $(this).attr('data-token')
    }
    callActionAjax({url: url, body: body})
  })

  $(document).on('change', '#datatable_wrapper .change-select', function() {
    let url = $(this).attr('data-url')
    let body = {
      value: $(this).val(),
      _token: $(this).attr('data-token')
    }
    callActionAjax({url: url, body: body})
  })

  function callActionAjax ({url, body}) {
    $.ajax({
      type: 'POST',
      url: url,
      data: body,
      success: function(res) {
        if (res.status) {
          window.renderedDataTable.ajax.reload(resetActionButtons, false)
          const event = new CustomEvent('update_quick_action', {detail: {value: true}})
          document.dispatchEvent(event)
        } else {
          Swal.fire({
            title: 'Error',
            text: res.message,
            icon: "error"
          })
        }
      }
    })
  }


  $(document).on('click', '#datatable_wrapper .button-status-change', function() {

    let url = $(this).attr('data-url')
    let body = {
      status: 1,
      _token: $(this).attr('data-token')
    }
    callActionAjax({url: url, body: body})
  })

  function callActionAjax ({url, body}) {
    $.ajax({
      type: 'POST',
      url: url,
      data: body,
      success: function(res) {
        if (res.status) {
          window.renderedDataTable.ajax.reload(resetActionButtons, false)
          const event = new CustomEvent('update_quick_action', {detail: {value: true}})
          document.dispatchEvent(event)
        } else {
          Swal.fire({
            title: 'Error',
            text: res.message,
            icon: "error"
          })
        }
      }
    })
  }

  


  //select row in datatable
  const dataTableRowCheck = (id, source = null) => {
    var dataType = source ? source.getAttribute('data-type') : null;
     
    checkRow();
    if ($(".select-table-row:checked").length > 0) {
        $("#quick-action-form").removeClass('form-disabled');

        document.getElementById("select-all-table").indeterminate = true;
        $("#quick-actions").find("input, textarea, button, select").removeAttr("disabled");
    } else {

        document.getElementById("select-all-table").indeterminate = false;
        $("#select-all-table").attr("checked", false);
        resetActionButtons();
    }

    if ($("#datatable-row-" + id).is(":checked")) {
        $("#row-" + id).addClass("table-active");
    } else {
        $("#row-" + id).removeClass("table-active");
    }

    
    const rowdIds = $("#datatable_wrapper .select-table-row:checked").map(function() {
        return $(this).val();
    }).get();

    const actionDropdown = document.getElementById('quick-action-type');
    
    if(dataType === 'category' || dataType === 'subcategory' || dataType === 'document'){
      actionDropdown.options[4].disabled = true;  
      actionDropdown.options[5].disabled = true;
      actionDropdown.options[3].disabled = false;
    }
    else if(dataType === 'booking'){
      actionDropdown.options[2].disabled = true;  
      actionDropdown.options[3].disabled = true;
      actionDropdown.options[1].disabled = false;
    }
    else if(dataType == null){
    }
    else{
      actionDropdown.options[3].disabled = true; // Restore option
      actionDropdown.options[4].disabled = true; // Permanently Delete option
      actionDropdown.options[2].disabled = false;
    }

    const baseUrl = document.querySelector('meta[name="baseUrl"]').getAttribute('content');

    $.ajax({
      type: 'POST',
      url: baseUrl + "/check-in-trash",  
      data: { ids: rowdIds, datatype: dataType },
      success: function(response) {
          if(response.all_in_trash == true){
            if(dataType === 'category' || dataType === 'subcategory' || dataType === 'document'){
              actionDropdown.options[4].disabled = false;  
              actionDropdown.options[5].disabled = false;
              actionDropdown.options[3].disabled = true;
            }
            else if(dataType === 'booking'){
              actionDropdown.options[2].disabled = false;  
              actionDropdown.options[3].disabled = false;
              actionDropdown.options[1].disabled = true;
            }
            else if(dataType == null){
            }
            else{
              actionDropdown.options[3].disabled = false; // Restore option
              actionDropdown.options[4].disabled = false; // Permanently Delete option
              actionDropdown.options[2].disabled = true;
            }

          }
      }
    });


  };
  window.dataTableRowCheck = dataTableRowCheck


  // const selectAllTable = (source) => {
  //   const checkboxes = document.getElementsByName("datatable_ids[]");
  //   for (var i = 0, n = checkboxes.length; i < n; i++) {

  //       if (!$("#" + checkboxes[i].id).prop('disabled')){
  //           checkboxes[i].checked = source.checked;
  //       }
  //       if ($("#" + checkboxes[i].id).is(":checked")) {
  //           $("#" + checkboxes[i].id)
  //               .closest("tr")
  //               .addClass("table-active");
  //           $("#quick-actions")
  //               .find("input, textarea, button, select")
  //               .removeAttr("disabled");
  //           if ($("#quick-action-type").val() == "") {
  //               $("#quick-action-apply").attr("disabled", true);
  //           }
  //       } else {
  //           $("#" + checkboxes[i].id)
  //               .closest("tr")
  //               .removeClass("table-active");
  //           resetActionButtons();
  //       }
  //   }

  //   checkRow()
  // };

  const selectAllTable = (source) => {
    var dataType = source.getAttribute('data-type');
    console.log(dataType);

    const checkboxes = document.getElementsByName("datatable_ids[]");
    const actionDropdown = document.getElementById('quick-action-type');
    const selectedIds = [];
    
    for (var i = 0, n = checkboxes.length; i < n; i++) {
      if (!$("#" + checkboxes[i].id).prop('disabled')) {
        checkboxes[i].checked = source.checked;
        if (checkboxes[i].checked) {
          selectedIds.push(checkboxes[i].value);
        }
      }
    }

    if(dataType === 'category' || dataType === 'subcategory' || dataType === 'document'){
      actionDropdown.options[4].disabled = true;  
      actionDropdown.options[5].disabled = true;
      actionDropdown.options[3].disabled = false;
    }
    else if(dataType === 'booking'){
      actionDropdown.options[2].disabled = true;  
      actionDropdown.options[3].disabled = true;
      actionDropdown.options[1].disabled = false;
    }
    else if(dataType == null){
    }
    else{
      actionDropdown.options[3].disabled = true; // Restore option
      actionDropdown.options[4].disabled = true; // Permanently Delete option
      actionDropdown.options[2].disabled = false;
    }

    const baseUrl = document.querySelector('meta[name="baseUrl"]').getAttribute('content');
  
    $.ajax({
      type: 'POST',
      url: baseUrl + "/check-in-trash",  
      data: { ids: selectedIds, datatype: dataType },
      success: function(response) {
          if(response.all_in_trash == true){
            if(dataType === 'category' || dataType === 'subcategory' || dataType === 'document'){
              actionDropdown.options[4].disabled = false;  
              actionDropdown.options[5].disabled = false;
              actionDropdown.options[3].disabled = true;
            }
            else if(dataType === 'booking'){
              actionDropdown.options[2].disabled = false;  
              actionDropdown.options[3].disabled = false;
              actionDropdown.options[1].disabled = true;
            }
            else if(dataType == null){
            }
            else{
              actionDropdown.options[3].disabled = false; // Restore option
              actionDropdown.options[4].disabled = false; // Permanently Delete option
              actionDropdown.options[2].disabled = true;
            }

          }
      }
    });

    $("#quick-actions").find("input, textarea, button, select").removeAttr("disabled");
    if ($("#quick-action-type").val() == "") {
      $("#quick-action-apply").attr("disabled", true);
    } 

    checkboxes.forEach((checkbox) => {
      const tableRow = $("#" + checkbox.id).closest("tr");
      if (checkbox.checked) {
        tableRow.addClass("table-active");
      } else {
        tableRow.removeClass("table-active");
      }
    });

    if ($(".select-table-row:checked").length === 0) {
      resetActionButtons();
      document.getElementById("select-all-table").indeterminate = false;
      $("#select-all-table").attr("checked", false);
    }

    checkRow()
  };
  

  window.selectAllTable = selectAllTable

  const checkRow = () => {
    if ($(".select-table-row:checked").length > 0) {
      $("#quick-action-form select").prop('disabled', false);

    } else {
      $("#quick-action-form select, #quick-action-form button").prop('disabled', true);
    }
  }


  $(document).ready(function() {

    $(".select-table-row").prop('checked', false);
    $("#quick-action-form select, #quick-action-form button").prop('disabled', true);
  });
  window.checkRow = checkRow
  


  //reset table action form elements

  const resetActionButtons = () => {
    checkRow()
    if(document.getElementById("select-all-table") !== undefined && document.getElementById("select-all-table") !== null) {
      document.getElementById("select-all-table").checked = false;

      $("#quick-actions")
          .find("input, textarea, button, select")
          .attr("disabled", "disabled");
        $('.quick-action-field').addClass('d-none');
        $('.quick-action-featured').addClass('d-none');
       
        $("#quick-action-form").find("select").val(null).trigger("change");
        $("#quick-action-form").find('.quick-action-field').find("select").val("1");
        $("#quick-action-form").find('.quick-action-featured').find("select").val("1")
    }
  };

  window.resetActionButtons = resetActionButtons

    jQuery(document).ready(function() {

        /*---------------------------------------------------------------------
        Tooltip
        -----------------------------------------------------------------------*/
        jQuery('[data-toggle="popover"]').popover();
        jQuery('[data-toggle="tooltip"]').tooltip();

        /*---------------------------------------------------------------------
        Fixed Nav
        -----------------------------------------------------------------------*/

        $(window).on('scroll', function () {
            if ($(window).scrollTop() > 0) {
                $('.iq-top-navbar').addClass('fixed');
            } else {
                $('.iq-top-navbar').removeClass('fixed');
            }
        });

        $(window).on('scroll', function () {
            if ($(window).scrollTop() > 0) {
                $('.white-bg-menu').addClass('sticky-menu');
            } else {
                $('.white-bg-menu').removeClass('sticky-menu');
            }
        });


       /*---------------------------------------------------------------------
        Sidebar Widget
        -----------------------------------------------------------------------*/

        jQuery(document).on("click", '.side-menu > li > a', function() {
            jQuery('.side-menu > li > a').parent().removeClass('active');
            jQuery(this).parent().addClass('active');
        });

        var parents = jQuery('li.active').parents('.submenu.collapse');

        parents.addClass('show');


        parents.parents('li').addClass('active');
        jQuery('li.active > a[aria-expanded="false"]').attr('aria-expanded', 'true');

        /*---------------------------------------------------------------------
        Page Loader
        -----------------------------------------------------------------------*/
        jQuery("#load").fadeOut();
        jQuery("#loading").delay().fadeOut("");

        /*---------------------------------------------------------------------
        Page Menu
        -----------------------------------------------------------------------*/
        jQuery(document).on('click', '.wrapper-menu', function() {
            jQuery(this).toggleClass('open');
        });

        jQuery(document).on('click', ".wrapper-menu", function() {
            jQuery("body").toggleClass("sidebar-main");
        });


      /*---------------------------------------------------------------------
       Close  navbar Toggle
       -----------------------------------------------------------------------*/

        jQuery('.close-toggle').on('click', function () {
            jQuery('.h-collapse.navbar-collapse').collapse('hide');
        });

        /*---------------------------------------------------------------------
        user toggle
        -----------------------------------------------------------------------*/
        jQuery(document).on('click', '.user-toggle', function() {
            jQuery(this).parent().addClass('show-data');
        });

        jQuery(document).on('click', ".close-data", function() {
            jQuery('.user-toggle').parent().removeClass('show-data');
        });
        jQuery(document).on("click", function(event){
        var $trigger = jQuery(".user-toggle");
        if($trigger !== event.target && !$trigger.has(event.target).length){
            jQuery(".user-toggle").parent().removeClass('show-data');
        }
        });

        /*---------------------------------------------------------------------
        Data tables
        -----------------------------------------------------------------------*/
        if($.fn.DataTable){
            const table = $('.data-table').DataTable();
        }


        /*---------------------------------------------------------------------
        Form Validation
        -----------------------------------------------------------------------*/


        window.addEventListener('load', function() {

            var forms = document.getElementsByClassName('needs-validation');

            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);

        /*---------------------------------------------------------------------
        Scrollbar
        -----------------------------------------------------------------------*/

        jQuery('.data-scrollbar').each(function () {
            var attr = $(this).attr('data-scroll');
            if (typeof attr !== typeof undefined && attr !== false){
            let Scrollbar = window.Scrollbar;
            var a = jQuery(this).data('scroll');
            Scrollbar.init(document.querySelector('div[data-scroll= "' + a + '"]'));
            }
        });

    });


    $(document).on('click', '[data-toggel-extra="side-nav"]', function () {
        const pannel = $(this).attr('data-expand-extra')
        $(pannel).addClass('active')
    })

    $(document).on('click', '[data-toggel-extra="side-nav-close"]', function () {
        const pannel = $(this).attr('data-expand-extra')
        $(pannel).removeClass('active')
    })

    $(document).on('click', '[data-toggel-extra="right-sidenav"]', function () {
        const target = $(this).data('target')
        $(target).addClass('active')
    })

    $(document).on('click', '[data-extra-dismiss="right-sidenav"]', function () {
        $(this).closest('.right-sidenav').removeClass('active')
    })

    $(document).on('click', '[data-toggle="end-call"]', function(){
        $(this).closest('.tab-pane').removeClass('active').removeClass('show')
        $($(this).attr('data-target')).tab('show')
        $('.chat-action').find('[data-toggle="tab"]').removeClass('active')
    })

    $(document).on('click', '[data-toggle-extra="tab"]', function () {
        const target = $(this).attr('data-target-extra')
        $('[data-toggle-extra="tab-content"]').removeClass('active')
        $(target).addClass('active')
        $(this).parent().find('.active').removeClass('active')
        $(this).addClass('active')
    })

    $('.dropdown-menu').on('click', function(event){
        event.stopPropagation();
    });


})(jQuery);
