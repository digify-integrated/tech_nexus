<div id="system-error-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="system-error-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="system-error-title">System Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row align-items-center mb-3">
                        <div class="col">
                            <h5>An error occured</h5>
                            <p>Please use the copy button to report the error to your support service.</p>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-warning" id="copy-error-message">Copy the full error to clipboard</button>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <div class="col">
                        <div class="alert alert-danger" role="alert" id="error-dialog"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>