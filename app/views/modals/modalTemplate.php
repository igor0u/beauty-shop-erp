<div class="modal fade mw-100 w-85 bd-modal-lg" id="<?php echo $modal['modalId']; ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span id="modalTitle" class="text-uppercase"><?php echo $modal['modalTitle']; ?></span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="grid-margin stretch-card">
                <div class="card border-0">
                    <?php require_once $modal['modalContent']; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .ui-autocomplete {
        z-index: 10000;
        max-height: 200px;
        overflow-y: auto;
        /* prevent horizontal scrollbar */
        overflow-x: hidden;
    }

    /* IE 6 doesn't support max-height
     * we use height instead, but this forces the menu to always be this tall
     */
    * html .ui-autocomplete {
        height: 100px;
    }
</style>