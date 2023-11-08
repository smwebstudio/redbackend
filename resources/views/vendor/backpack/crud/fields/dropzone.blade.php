@php
    $field['configuration'] ??= [];
    $defaultConfig = [
        'url' => $field['configuration']['url'] ?? backpack_url($crud->entity_name . '/dropzone/upload'),
        'headers' => array_merge($field['configuration']['headers'] ?? [], ['X-CSRF-TOKEN' => csrf_token()]),
        'parallelUploads' => $field['configuration']['parallelUploads'] ?? 4,
        'uploadMultiple' => true,
        'maxThumbnailFilesize' => $field['configuration']['maxThumbnailFilesize'] ?? 100
    ];

    $field['configuration'] = array_merge($field['configuration'], $defaultConfig);
    $field['value'] = old_empty_or_null($field['name'], '') ?? $field['value'] ?? $field['default'] ?? '';

    if(is_string($field['value']) && !empty($field['value'])) {
        $field['value'] = json_decode($field['value'], true) ?? '';
    }
    $temporaryDisk = CRUD::get('dropzone.temporary_disk');
    $temporaryDirectory = CRUD::get('dropzone.temporary_folder');

    if (is_array($field['value'])) {
        $serverFiles = [];
        $newPaths = [];

        foreach ($field['value'] as $key => $path) {
            $disk = strpos($path, $temporaryDirectory) !== false ? $temporaryDisk : $field['disk'];

            $newPath = str_replace("//", "/", $path);
            $serverFiles[] = [
                'name' => basename($newPath),
                'size' => \Storage::disk($disk)->size($newPath),
                'mime' => \Storage::disk($disk)->mimeType($newPath),
                'path' => $newPath,
                'url' => Storage::disk($disk)->url($newPath),
            ];

            $newPaths[] = $newPath;
        }


        $field['server_files'] = json_encode($serverFiles, true);
        $field['value'] = json_encode($newPaths, true);

    }

    $readonly = $field['attributes']['readonly'] ?? false;
    $disabled = $field['attributes']['disabled'] ?? false;
@endphp

@include('crud::fields.inc.wrapper_start')
<input
    type="hidden"
    name="{{ $field['name'] }}"
    bp-field-main-input
    value="{{ $field['value'] }}"
    @include('crud::fields.inc.attributes')
>

<label>{!! $field['label'] !!}</label>

@include('crud::fields.inc.translatable_icon')
<div class="text-center p-4">
    <span id="deleteAllButton" class="button btn bg-red text-white">Ջնջել բոլորը</span>
</div>
<div
    class="dropzone dropzone-target {{ $disabled ? 'disabled' : '' }} {{ $readonly ? 'readonly' : '' }}"
    data-config="{{json_encode($field['configuration'])}}"
    data-init-function="bpFieldInitDropzoneElement"
    data-name="{{ $field['name'] }}"
    data-server-files="{{ $field['server_files'] ?? '' }}"
    data-form-operation="{{ $crud->get('dropzone.formOperation') }}"
    data-temp-upload-folder-name="{{ $temporaryDirectory }}"
    data-is-dropzone-active="{{ var_export(($disabled || $readonly) ? false : true) }}">
    <div class="dz-message">
        <button class="dz-button" type="button">Սեղմեք կամ քաշեք նկարներն այստեղ վերբեռնելու համար։</button>
    </div>
</div>




{{-- HINT --}}
@if (isset($field['hint']))
    <p class="help-block">{!! $field['hint'] !!}</p>
@endif

<div class="hidden hidden-container"></div>
@include('crud::fields.inc.wrapper_end')

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}

{{-- FIELD CSS - will be loaded in the after_styles section --}}
@push('crud_fields_styles')
    {{-- include dropzone css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css">
    @basset('https://www.unpkg.com/dropzone@6.0.0-beta.2/dist/dropzone.css')
    @bassetBlock('backpack/pro/fields/dropzone-field.css')
    <style>

        .modal {
            z-index: 1050; /* Increase the z-index value if necessary */
            position: fixed;
        }

        /*.modal-backdrop {*/
        /*    display: none !important; !* Increase the z-index value if necessary *!*/
        /*}*/

        /*.modal.show .modal-dialog {*/
        /*    position: fixed !important;*/
        /*    margin: 0px auto;*/
        /*    width: 800px;*/
        /*    left: 40%;*/
        /*    z-index: 1090;*/
        /*}*/

        .dropzone {
            border: 1px solid rgba(0, 40, 100, 0.2);
            min-height: 4.5rem;
        }

        .dropzone.disabled, .dropzone.readonly {
            background-color: #f8f9fa;
        }

        .dropzone .dz-preview.dz-image-preview .dz-details {
            cursor: move;
        }

        .actionsDropzone {
            display: flex;
            gap: 12px;
            padding: 8px;
            justify-content: center;
        }

        .actionsDropzone div {
            font-size: 24px !important;
        }

        .dropzone .actionsDropzone div:hover i {
            cursor: pointer;
            color: #00bb00;
        }

        .dropzone .dz-preview {
            visibility: hidden;
        }

        .dropzone .dz-preview .dz-image {
            border-radius: 20px;
            overflow: hidden;
            width: 200px;
            height: 200px;
            position: relative;
            display: block;
            z-index: 10;
        }

        .dropzone .dz-progress {
            visibility: visible;
        }

        .dropzone .dz-message {
            margin: 0 auto;
        }

        .dropzone.dz-started .dz-message {
            display: block;
        }

        .dropzone.disabled .dz-message, .dropzone.readonly .dz-message {
            display: none;
        }

        .dropzone.disabled .dz-preview, .dropzone.readonly .dz-preview {
            opacity: 0.5;
        }

        .dropzone .dz-preview .dz-image, .dropzone .dz-preview.dz-file-preview .dz-image {
            border-radius: 0.4rem;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .dropzone.disabled .dz-preview .dz-image, .dropzone.disabled .dz-preview.dz-file-preview .dz-image, .dropzone.readonly .dz-preview .dz-image, .dropzone.readonly .dz-preview.dz-file-preview .dz-image {
            border-radius: 0.4rem;
        }

        .dropzone.dz-clickable {
            cursor: auto;
        }

        .dropzone .dz-preview .dz-remove i {
            cursor: pointer;
        }

        .dropzone.disabled .dz-remove, .dropzone.readonly .dz-remove {
            display: none;
        }

        .dropzone .dz-preview .dz-image img {
            height: 100%;
            object-fit: cover;
        }


        .dropzone .dz-remove i::before {
            padding-top: 2px;
        }

        [data-bs-theme=dark] .dropzone .dz-preview.dz-image-preview {
            background: inherit;
        }

        [data-bs-theme=dark] .dropzone {
            border: 1px solid rgba(78, 78, 79, 0.3);
        }

        [data-bs-theme=dark] .dropzone.disabled, [data-bs-theme=dark] .dropzone.readonly {
            background-color: inherit;
            color: inherit;
        }

    </style>
    @endBassetBlock
@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
    {{-- include dropzone js --}}
    @basset('https://www.unpkg.com/dropzone@6.0.0-beta.2/dist/dropzone-min.js')
    @basset('https://www.unpkg.com/sortablejs@1.15.0/Sortable.min.js')

    @bassetBlock('backpack/pro/fields/dropzone-field.js')
    <script>
        function bpFieldInitDropzoneElement(element) {
            const dz = element[0];
            let $dropzoneConfig = JSON.parse(dz.dataset.config);
            let input = dz.parentNode.querySelector('input[type="hidden"]');
            let dropzoneHiddenContainer = dz.parentNode.querySelector('div.hidden-container');
            let formOperation = dz.dataset.formOperation;
            // always ensure that the random id starts with a letter, it will break the selector if it starts
            // with a number. **sad pikachu face**
            let randomId = "abcdefghijklmnopqrstuvwxyz"[Math.floor(Math.random() * 26)] + Math.random().toString(36).substring(2, 10 + 2);

            let isDropzoneActive = dz.dataset.isDropzoneActive;

            dz.setAttribute('data-name', input.getAttribute('name'));

            dropzoneHiddenContainer.classList.add(randomId + '-bp-dropzone-hidden-input');

            $dropzoneConfig.paramName = input.getAttribute('data-repeatable-input-name') !== null ? dz.parentNode.parentNode.getAttribute('data-repeatable-identifier') + '#' + input.getAttribute('data-repeatable-input-name') : input.getAttribute('name');
            $dropzoneConfig.hiddenInputContainer = 'div.' + randomId + '-bp-dropzone-hidden-input';

            Dropzone.autoDiscover = false;

            if (!$dropzoneConfig.init) {
                $dropzoneConfig.init = function () {
                    this.on('addedfile', function (file) {
                        var actionsDropzone = Dropzone.createElement('<div class="actionsDropzone flex justify-center "></div>');
                        var removeButton = Dropzone.createElement('<div class="dz-remove" data-dz-remove=""><i class="la la-trash"></i></div>');
                        var publishButton = Dropzone.createElement('<div class="dz-publish" ><i class="las la-eye-slash"></i></div>');
                        var mainButton = Dropzone.createElement('<div class="dz-main" ><i class="lar la-star"></i></div>');
                        var _this = this;



                        $currentMainFile = $('input[name="main_image_file_path"]').val();

                        console.log(file)
                        console.log($currentMainFile)

                        if(file.path === $currentMainFile) {
                            $(mainButton).addClass('text-success');
                            $(mainButton).html('<i class="las la-star"></i>');
                        }

                        removeButton.addEventListener('click', function (e) {
                            e.preventDefault();
                            e.stopPropagation();
                            if (isDropzoneActive) {
                                _this.removeFile(file);
                            }
                        });

                        mainButton.addEventListener('click', function (e) {
                            e.preventDefault();
                            e.stopPropagation();
                            if (isDropzoneActive) {

                                var filePath = file.path;

                                if (filePath.startsWith("estate/photos/")) {
                                    filePath = filePath.slice(14);
                                }

                                $('input[name="main_image_file_path"]').val(filePath);
                                $('input[name="main_image_file_name"]').val(file.name);
                                $('input[name="main_image_file_path_thumb"]').val(filePath);


                                $('.dz-main.text-success').html('<i class="lar la-star"></i>');
                                $('.dz-main.text-success').removeClass('text-success');
                                $(this).addClass('text-success');
                                $(this).html('<i class="las la-star"></i>');
                                console.log('Updated input value to: ' + file.path);
                            }
                        });

                        file.previewElement.appendChild(actionsDropzone);
                        actionsDropzone.appendChild(publishButton);
                        actionsDropzone.appendChild(removeButton);
                        actionsDropzone.appendChild(mainButton);

                        input.dispatchEvent(new Event('change', {bubbles: true}));
                    });

                    const serverFiles = dz.dataset.serverFiles;

                    if (!serverFiles) {
                        return;
                    }

                    let files = JSON.parse(serverFiles);

                    files.forEach((file) => {
                        this.emit('addedfile', file);
                        if (file.url && file.mime?.startsWith('image')) {
                            this.emit('thumbnail', file, file.url);
                        }
                        file.previewElement.querySelector('.dz-progress').style.visibility = 'hidden';
                        file.previewElement.style.visibility = 'visible';
                    });
                };
            }

            if (!$dropzoneConfig.successmultiple) {
                $dropzoneConfig.successmultiple = function (files, response, request) {
                    let inputFiles = input.value ?? [];

                    if (inputFiles) {
                        inputFiles = JSON.parse(inputFiles);
                        inputFiles = Object.values(inputFiles)
                    }

                    newFiles = response.files;

                    let mergedFiles = [...inputFiles, ...newFiles];
                    mergedFiles = mergedFiles.length > 0 ? mergedFiles : '';
                    input.value = JSON.stringify(mergedFiles);

                    dz.parentNode.classList.remove('text-danger');
                    dz.parentNode.querySelector('.invalid-feedback')?.remove();

                    files.forEach(function (file, index) {
                        file.previewElement.style.visibility = 'visible';
                        file.upload.filename = response.files[index];
                        let uploadedFileNameContainer = file.previewElement.querySelector('[data-dz-name]');
                        uploadedFileNameContainer.innerHTML = response.files[index];
                    });
                };
            }

            if (!$dropzoneConfig.error) {
                $dropzoneConfig.error = function (file, response, request) {
                    if (response) {
                        let errorBagName = $dropzoneConfig.paramName;
                        // it's a repeatable dropzone container
                        if (errorBagName.includes('#')) {
                            errorBagName = errorBagName.replace('#', '.0.');
                        }
                        let errorMessages = response.errors[errorBagName].join('<br/>');
                        let errorNode = dz.querySelector('.dz-error-message span');
                        // remove previous error messages
                        dz.parentNode.querySelector('.invalid-feedback')?.remove();

                        // add the red text classes
                        dz.parentNode.classList.add('text-danger');

                        // create the error message container
                        let errorContainer = document.createElement("div");
                        errorContainer.classList.add('invalid-feedback', 'd-block');
                        errorContainer.innerHTML = errorMessages;
                        dz.parentNode.appendChild(errorContainer);

                        // remove the preview for failed uploads
                        file.previewElement.remove();
                    }
                };
            }

            $dropzoneConfig.removedfile = function (file, xhr) {
                let filePath = file;

                if (file.xhr) {
                    filePath = file.upload.filename;
                }

                let tempUploadFolderName = dz.dataset.tempUploadFolderName;
                let inputFiles = input.value;
                let files = inputFiles ? JSON.parse(inputFiles) : [];

                console.log(78)
                console.log(filePath)
                console.log(tempUploadFolderName)
                if (!filePath.path && filePath.includes(tempUploadFolderName)) {

                    $.ajax({
                        url: '{{ backpack_url($crud->entity_name . '/dropzone/delete') }}',
                        type: 'POST',
                        data: `file=${filePath}`,
                        success: function (data) {
                            if (data.success) {
                                file.previewElement?.remove();
                                files.splice(files.findIndex(obj => (obj === filePath)), 1);
                                input.value = JSON.stringify(files);
                            }
                        }
                    });
                } else {
                    files = Array.isArray(files) ? files : Object.values(files);

                    let fileToDelete = files.find((obj) => obj === filePath.path);

                    if (fileToDelete) {
                        file.previewElement?.remove();
                        files.splice(files.findIndex(obj => (obj === filePath.path)), 1);
                    }
                    files = files.length > 0 ? files : '';
                    input.value = JSON.stringify(files);
                }
                input.dispatchEvent(new Event('change', {bubbles: true}));
            };

            $dropzoneConfig.sending = function (file, xhr, formData) {
                formData.append('previousUploadedFiles', input.value);
                formData.append('operation', formOperation);
                formData.append('fieldName', $dropzoneConfig.paramName);
            };


            let dropzone = new Dropzone(dz, $dropzoneConfig);

            const deleteAllButton = document.getElementById("deleteAllButton");
            deleteAllButton.addEventListener("click", function () {
                // Remove all files from Dropzone
                console.log(89)
                dropzone.removeAllFiles();
            });

            let sortable = new Sortable(dz, {
                handle: '.dz-preview',
                draggable: '.dz-preview',
                scroll: false,
                onEnd: function (evt) {
                    const currentSort = input.value;

                    let files = currentSort ? JSON.parse(currentSort) : [];
                    var newSort = files.splice(evt.oldIndex - 1, 1)[0];
                    files.splice(evt.newIndex - 1, 0, newSort);

                    input.value = JSON.stringify(files);
                },
                onChange: function (evt) {
                    $(input).trigger('change');
                }
            });

            function disableDropzone() {
                $(dz).addClass('disabled');
                $(dz).siblings().find('.dz-hidden-input').prop('disabled', true);
                sortable.options.disabled = true;
                dropzone.removeEventListeners();
            }

            function enableDropzone() {
                $(dz).removeClass('disabled');
                $(dz).siblings().find('.dz-hidden-input').prop('disabled', false);
                sortable.options.disabled = false;
                dropzone.setupEventListeners();
            }

            input.addEventListener('CrudField:disable', function (e) {
                disableDropzone();
            });

            input.addEventListener('CrudField:enable', function (e) {
                enableDropzone();
            });

            element.find('.dz-filename').on('click', function (e) {
                const serverFiles = dz.dataset.serverFiles;
                if (!serverFiles || !e.currentTarget.innerText) {
                    return;
                }

                let files = JSON.parse(serverFiles);


                let imageIndex = -1;
                files.forEach((file, index) => {
                    if (file.name == e.currentTarget.innerText) {
                        imageIndex = index;
                        const lightboxImages = files.map((file) => file.url);

                        // Create and show the Bootstrap modal
                        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
                        modal.show();

                        // Set the current image in the modal
                        const modalImage = document.getElementById('modalImage');
                        modalImage.src = file.url;
                        modalImage.alt = file.name;

                        // Set the navigation controls
                        const prevBtn = document.getElementById('prevBtn');
                        const nextBtn = document.getElementById('nextBtn');
                        prevBtn.addEventListener('click', function () {
                            const prevIndex = (imageIndex - 1 + files.length) % files.length;
                            modalImage.src = lightboxImages[prevIndex];
                            modalImage.alt = files[prevIndex].name;
                        });
                        nextBtn.addEventListener('click', function () {
                            const nextIndex = (imageIndex + 1) % files.length;
                            console.log(nextIndex);
                            modalImage.src = lightboxImages[nextIndex];
                            modalImage.alt = files[nextIndex].name;
                        });
                    }
                });
            });

            if (isDropzoneActive !== 'true') {
                disableDropzone();
            }
        }
    </script>
    @endBassetBlock
@endpush

{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
<div id="imageModal" class="modal fade" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <img id="modalImage" src="" alt="" class="img-fluid">
            </div>
            <div class="modal-footer">
                <button id="prevBtn" class="btn btn-secondary" type="button">&laquo; Previous</button>
                <button id="nextBtn" class="btn btn-secondary" type="button">Next &raquo;</button>
            </div>
        </div>
    </div>
</div>
