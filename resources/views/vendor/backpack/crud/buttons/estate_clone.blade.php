

    @if (isset($entry->contract_type_id) )
        <a href="javascript:void(0)" onclick="estateCloneEntry(this)" data-route="{{ url($crud->route.'/'.$entry->getKey().'/clone') }}" class="btn btn-sm btn-link" data-button-type="clone"><i class="la la-copy"></i> </a>


    {{-- Button Javascript --}}
    {{-- - used right away in AJAX operations (ex: List) --}}
    {{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
    @push('after_scripts') @if (request()->ajax()) @endpush @endif
    <script>

        if (typeof estateCloneEntry != 'function') {
            $("[data-button-type=delete]").unbind('click');

            var inputOptions = {
                '1': 'Վաճառք',
                '2': 'Վարձ.',
                '3': 'Օրավարձ'
            };

            var excludedOption = '{{$entry->contract_type_id}}'; // Change this to the value you want to exclude

            if (inputOptions[excludedOption]) {
                delete inputOptions[excludedOption];
            }

            function estateCloneEntry(button) {
                // ask for confirmation before deleting an item
                // e.preventDefault();
                var route = $(button).attr('data-route');
                var email = $(button).attr('data-email');

                Swal.fire({
                    title: "Կրկնօրինակել",
                    html: 'Ներկա գործարքային տիպը։ {{$entry->contract_type->name_arm}}',
                    input: 'select',
                    inputOptions: inputOptions,
                    inputPlaceholder: 'Ընտրել նոր  գործարքային տիպ',
                    showConfirmButton: true,
                    confirmButtonText: "Հաստատել",
                    cancelButtonText: "Չեղարկել",
                    confirmButtonColor: '#7c69ef',
                    showCancelButton: true,
                    inputValidator: (value) => {
                        return new Promise((resolve) => {
                            if (value) {
                                resolve()
                            } else {
                                resolve('Խնդրում ենք  ընտրել նոր  գործարքային տիպ։')
                            }
                        })
                    },

                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: route,
                            type: 'POST',
                            data: {'contract_type': result.value},
                            dataType: 'text',
                            success: function (result) {
                                if (result == 1) {
                                    // Redraw the table
                                    if (typeof crud != 'undefined' && typeof crud.table != 'undefined') {
                                        // Move to previous page in case of deleting the only item in table
                                        if (crud.table.rows().count() === 1) {
                                            crud.table.page("previous");
                                        }

                                        crud.table.draw(false);
                                    }

                                    // Show a success notification bubble
                                    Swal.fire({
                                        text: "Կրկնօրինակված է", //TODO: translations
                                        icon: "success",
                                        timer: 8000,
                                        timerProgressBar: true,
                                    }).then(function(){
                                            location.reload();
                                        }
                                    );

                                    // Hide the modal, if any
                                    $('.modal').modal('hide');
                                } else {
                                    Swal.fire({
                                        title: "Something went wrong.",
                                        text: result,
                                        icon: "error",
                                        timer: 4000,
                                        buttons: false,
                                    });
                                }
                            },
                            error: function (err) {
                                // Show an alert with the result
                                Swal.fire({
                                    title: err.statusText,
                                    icon: "error",
                                    timer: 4000,
                                    buttons: false,
                                });
                            }
                        });
                    }
                });

            }
        }

    </script>
    @if (!request()->ajax()) @endpush @endif
    @endif
