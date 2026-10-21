@props([
    'id',
    'labelledby',
])

<div class="modal fade" {{ $id ? "id=$id" : "" }} tabindex="-1" aria-labelledby="{{ $labelledby }}" aria-hidden="true">
    <div class="modal-dialog">
          <div class="modal-content p-0 m-0 ">
            <div class="modal-header" >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#273746">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
             <div class="card-body p-0 m-0 ">
                 <div class="modal-body pb-0">
                <div class="form-group">
                {{ $slot }}
                </div>
                </div>
             </div>
        </div>
    </div>
</div>