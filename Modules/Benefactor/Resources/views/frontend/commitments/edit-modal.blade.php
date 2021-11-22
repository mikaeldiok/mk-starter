<!-- Modal -->
<div class="modal fade" id="editCommitment" tabindex="1" role="dialog" aria-labelledby="editCommitment" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ubah Komitmen anda</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @include("benefactor::frontend.commitments.edit")
      </div>
    </div>
  </div>
</div>