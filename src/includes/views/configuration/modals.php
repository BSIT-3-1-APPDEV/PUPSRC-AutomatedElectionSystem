<?php

class Modals
{
    static function getWarningModal(
        $subtitle,
        $message,
        $button = '<button class="btn btn-secondary px-sm-5 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6" aria-label="Close" value="false">Cancel</button>
                                            <button class="btn btn-warning px-sm-5 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6" value="true">Discard</button>'
    ) {
        echo <<<HTML
                <div class="modal fade show warning-modal" id="warning-modal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-body">
                        <div class="prompt-content p-4">
                        <img src="src/images/resc/yellow-warning.png" class="main-icon" alt="warning icon">
                        <p class="fw-bold fs-3 spacing-4">{$subtitle}</p>
                        <p class="fw-medium spacing-5">
                            {$message}
                        </p>
                        <div class="prompt-action">
                            {$button}
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            HTML;
    }

    static function getDangerModal()
    {
        echo '<div class="modal fade" id="danger-modal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">

                            <div class="row p-4">
                                <div class="col-md-12 pb-3">
                                    <div class="text-center">
                                        <div class="col-md-12 p-3">
                                            <img src="src/images/resc/warning.png" alt="iVote Logo">
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 pb-3 confirm-delete">
                                                <p class="fw-bold fs-3 danger spacing-4">Confirm Delete?</p>
                                                <p class="pt-2 fw-medium spacing-5">The account(s) will be deleted and moved to
                                                    Recycle Bin.
                                                    Are you sure you want to delete?
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 pt-3 text-center">
                                    <div class="d-inline-block">
                                        <button class="btn btn-light px-sm-5 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6" onClick="closeModal()" aria-label="Close">Cancel</button>
                                    </div>
                                    <div class="d-inline-block">
                                        <form class="d-inline-block">
                                            <input type="hidden" id="voter_id" name="voter_id" value="">
                                            <button class="btn btn-danger px-sm-5 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6" type="submit" id="confirm-delete" value="delete" disabled>Delete</button>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>';
    }

    static function getSuccessModal()
    {
        echo '<div class="modal " id="sucess-modal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="text-center">
                                <div class="col-md-12">
                                    <img src="src/images/resc/check-animation.gif" class="check-perc" alt="iVote Logo">
                                </div>
                                <div class="row">
                                    <div class="col-md-12 pb-3">
                                        <p class="fw-bold fs-3 success-color spacing-4">Successfully Created!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
    }
}
