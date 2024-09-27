(function ($) {
  'use strict';
  if ($("#fileuploader").length) {
    $("#fileuploader").uploadFile({
      url: "/upload/product/{{$product->id}}/image",
      fileName: "image",
      onSuccess: function(files, response, xhr, pd) {
        console.log(xhr.status)
        if (xhr.status === 200) {
          location.reload();
        }
      }
    });
  }
})(jQuery);