<div class="modal fade" id="sweetAlertModal" tabindex="-1" role="dialog" aria-labelledby="sweetAlertModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sweetAlertModalLabel">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{ $text }}</p>
            </div>
            <div class="modal-footer">
                @if($showCancelButton)
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ $cancelButtonText }}</button>
                @endif
                <button type="button" class="btn btn-primary" id="confirmButton">{{ $confirmButtonText }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#sweetAlertModal').modal('show');

        $('#confirmButton').click(function() {
            @if($confirmCallback)
                {!! $confirmCallback !!}
            @endif
            $('#sweetAlertModal').modal('hide');
        });
    });
</script>
