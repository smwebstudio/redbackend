

  @if ($crud->hasAccess('create'))
      <a href="javascript:void(0)" onclick="downloadEstateImages(this)" data-route="{{ url($crud->route.'/'.$entry->getKey().'/download-estate-images') }}" title="Ներբեռնել բոլոր նկարները"
         class="btn btn-sm btn-link" data-button-type="clone">
          <i class="las la-cloud-download-alt"></i></a>
  @endif

  {{-- Button Javascript --}}
  {{-- - used right away in AJAX operations (ex: List) --}}
  {{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
  @push('after_scripts') @if (request()->ajax()) @endpush @endif
  <script>
      if (typeof downloadEstateImages != 'function') {
          $("[data-button-type=clone]").unbind('click');

          function downloadEstateImages(button) {
              // ask for confirmation before deleting an item
              // e.preventDefault();
              var button = $(button);
              var route = button.attr('data-route');

              $.ajax({
                  url: route,
                  type: 'POST',
                  success: function(result) {
                      if (result.zipFileUrl) {
                          // Create a hidden anchor tag to trigger the download
                          var downloadLink = document.createElement('a');
                          downloadLink.href = result.zipFileUrl;
                          downloadLink.download = result.zipName; // Specify the desired filename

                          // Trigger a click event to start the download
                          downloadLink.click();
                      } else {
                          // Handle the case where the response doesn't contain the file URL
                          console.error('No download URL found in the response.');
                      }


                      if (typeof crud !== 'undefined') {
                          crud.table.ajax.reload();
                      }
                  },
                  error: function(result) {
                      // Show an alert with the result
                      new Noty({
                          type: "warning",
                          text: "Something went wrong. Please try again."
                      }).show();
                  }
              });
          }
      }

      // make it so that the function above is run after each DataTable draw event
      // crud.addFunctionToDataTablesDrawEventQueue('downloadEstateImages');
  </script>
  @if (!request()->ajax()) @endpush @endif
