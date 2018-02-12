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
    @if ($options['help_block']['text'] && !$options['is_child'])
        <{{ $options['help_block']['tag'] }} {!! $options['help_block']['helpBlockAttrs']  !!}>
        {{ $options['help_block']['text'] }}
        </{{ $options['help_block']['tag'] }} >
    @endif

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