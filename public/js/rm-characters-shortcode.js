jQuery(document).ready(function ($) {
  $("#rick-morty-character-form").on("submit", function (e) {
    e.preventDefault();
    $("#rick-morty-search-notice").hide();
    $("#rick-morty-load-more").hide();

    var searchParams = {
      action: rmAjax.rm_action,
      _ajax_nonce: rmAjax.nonce,
      name: $('input[name="name"]').val(),
      species: $('select[name="species"]').val(),
      page: 1,
    };

    $.ajax({
      url: rmAjax.ajaxurl,
      type: "POST",
      data: searchParams,
      success: function (response) {
        if (response.posts && response.posts.length > 0) {
          var contentHtml = "";
          contentHtml = "";
          response.posts.forEach(function (post) {
            contentHtml += rm_generateCharacterHtml(post);
          });
          $("#rick-morty-character-list").html(contentHtml);
          $("#rick-morty-load-more").show();
        } else {
          $("#rick-morty-character-list").html("");
          $("#rick-morty-search-notice").text(response.data.message);
          $("#rick-morty-search-notice").show();
        }
      },
      error: function () {
        $("#rick-morty-search-notice").text("Error loading characters.");
        $("#rick-morty-search-notice").show();
      },
    });
  });

  $("#rick-morty-load-more").on("click", function () {
    var button = $(this);
    var page = button.data("page");

    var moreParams = {
      action: rmAjax.rm_action,
      _ajax_nonce: rmAjax.nonce,
      name: $('input[name="name"]').val(),
      species: $('select[name="species"]').val(),
      page: page,
    };

    $.ajax({
      url: rmAjax.ajaxurl,
      type: "POST",
      data: moreParams,
      success: function (response) {
        if (response.posts && response.posts.length > 0) {
          var contentHtml = "";
          response.posts.forEach(function (post) {
            contentHtml += rm_generateCharacterHtml(post);
          });
          $("#rick-morty-character-list").append(contentHtml);
          button.data("page", page + 1);
        } else {
          $("#rick-morty-search-notice").text(
            response.data.message || "No more characters to load."
          );
          $("#rick-morty-search-notice").show();
          button.hide();
        }
      },
      error: function () {
        $("#rick-morty-search-notice").text("Error loading more characters.");
        $("#rick-morty-search-notice").show();
      },
    });
  });
});

function rm_generateCharacterHtml(post) {
  var html = '<div class="entry-card">';
  if (post.meta.image) {
    html +=
      '<img src="' +
      post.meta.image +
      '" alt="' +
      post.title +
      '" class="entry-card-image">';
  }
  html += '<div class="entry-card-details">';
  html += '<h2 class="entry-card-title">' + post.title + "</h2>";
  if (post.meta.species) {
    html += '<p class="entry-card-meta">' + post.meta.species + "</p>";
  }
  if (post.meta.status) {
    html += '<p class="entry-card-meta">' + post.meta.status + "</p>";
  }
  if (post.meta.type) {
    html += '<p class="entry-card-meta">' + post.meta.type + "</p>";
  }
  if (post.meta.gender) {
    html += '<p class="entry-card-meta">' + post.meta.gender + "</p>";
  }
  html += "</div></div>";
  return html;
}
