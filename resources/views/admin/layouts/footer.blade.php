 <footer class="d-footer">
    <div class="row align-items-center justify-content-between">
        <div class="col-auto">
        <p class="mb-0">© {{ date('Y') }} IGRCFP. All Rights Reserved.</p>
        </div>
        <div class="col-auto">
        <p class="mb-0">Made by <span class="text-primary-600">Tyneside Innovation</span></p>
        </div>
    </div>
</footer>
@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <!-- <script>
        ClassicEditor
            .create(document.querySelector('#editor'), {
                // Optional: Basic toolbar configuration
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'bulletedList', 'numberedList', 'blockQuote', '|',
                    'link', 'imageUpload', 'insertTable', '|',
                    'undo', 'redo'
                ]
            })
            .then(editor => {
                // Update hidden input when editor content changes
                editor.model.document.on('change:data', () => {
                    document.getElementById('description').value = editor.getData();
                });
                
                // Also update on form submit
                document.querySelector('form').addEventListener('submit', () => {
                    document.getElementById('description').value = editor.getData();
                });
            })
            .catch(error => {
                console.error(error);
            });
    </script> -->
@endpush