<div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-danger" style="border-radius:30px;" >
            <div class="modal-header border-0">

                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-white text-center">
            <h2 class="text-white">Are you Sure?</h2>
            <h4 class="text-white">Do you want to delete this record?</h4>
            </div>
            <div class="modal-footer border-0 d-flex justify-content-center">
                <button type="button" class="btn btn-dark-outline" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning" onclick="confirmDelete()">Confirm</button>
            </div>
        </div>
    </div>
</div>