<div class="form-group">
    <label for="{{ $name }}" class="control-label required">{{ $options['label'] }}</label>
    <div class='input-group date' id='datetimepicker'>
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-time"></span>
        </span>
        <input type='text' value="{{ $options['value'] }}" name="{{ $name }}" class="form-control" />
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-time"></span>
        </span>
    </div>
</div>
@push('scripts')
<script type="text/javascript">
    $(function () {
        $('#datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            sideBySide: true,
            showClose: true,
            widgetPositioning: {
                horizontal: 'left',
            }
        });
    });
</script>
@endpush