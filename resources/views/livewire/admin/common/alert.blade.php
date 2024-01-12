<div>
    @if(!empty($message))
        <div class="alert @if($isSuccess) alert-success @else alert-danger @endif alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>
