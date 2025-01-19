jQuery(document).ready(function ($) {
  $("#import-characters").on("click", function () {
    var import_button = $(this);
    import_button.prop("disabled", true);
    $("#import-loading-message").show();

    $.ajax({
      url: rickMortyAjax.ajaxurl,
      type: "POST",
      dataType: "json",
      data: {
        action: rickMortyAjax.rm_action,
        _ajax_nonce: rickMortyAjax.nonce,
      },
      success: function (response) {
        if (response.success) {
          $("#import-messages").html(
            '<div class="notice notice-success is-dismissible"><p>' +
              response.data.message +
              "</p></div>"
          );
        } else {
          $("#import-messages").html(
            '<div class="notice notice-error is-dismissible"><p>' +
              response.data.message +
              "</p></div>"
          );
        }
      },
      error: function (xhr) {
        var errorMessage = xhr.status + ": " + xhr.statusText;
        $("#import-messages").html(
          '<div class="notice notice-error is-dismissible"><p>' +
            errorMessage +
            "</p></div>"
        );
      },
      complete: function () {
        $("#import-loading-message").hide();
        import_button.prop("disabled", false);
      },
    });
  });
});
