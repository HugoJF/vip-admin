<div class="form-group">
    {!! Form::label($name, $options['label']) !!}
    <textarea name="{{ $name }}" id="summernote_{{ str_replace('-', '_', $name) }}">
        {{ $options['value'] }}
    </textarea>
</div>

@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#summernote_{{ str_replace('-', '_', $name) }}').summernote({
            toolbar: [
                // [groupName, [list of button]]
                ['fontstyle', ['style', 'height']],
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['back', ['undo', 'redo']],
                ['fontsize', ['fontname', 'fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['extra', ['link', 'picture', 'video']],
                ['options', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>
@endpush