<div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
    <div class="custom-switch-inner">
        <input type="checkbox" class="custom-control-input bg-primary change_status" data-type="role" id="{{ $role->id }}" data-id="{{ $role->id }}" {{ $role->status ? 'checked' : '' }} value = "{{ $role->id }}">
        <label class="custom-control-label" for="{{ $role->id }}" data-on-label="" data-off-label=""></label>
    </div>
</div>