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

    static function getDangerModal(
        $subtitle,
        $message,
        $button = '<button class="btn btn-secondary px-sm-5 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6" aria-label="Close" value="false">Cancel</button>
                                            <button class="btn btn-danger px-sm-5 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6" value="true">Discard</button>'
    ) {
        echo <<<HTML
                <div class="modal fade show danger-modal" id="danger-modal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-body">
                        <div class="prompt-content p-4">
                        <img src="src/images/resc/warning.png" class="main-icon" alt="warning icon">
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

    static function getDeleteModal(
        $button = '',
        $hasCloseButton = false,
        $subtitle = 'Confirm Delete?',
        $message = "<p>A heads up: this action cannot be undone!</p><p>Type 'Confirm Delete' to proceed</p>",
    ) {
        echo <<<HTML
                    <div class="modal fade show danger-modal" id="delete-modal" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                HTML;


        if ($hasCloseButton) {
            echo <<<HTML
                    <div class="modal-header "><h5 class="modal-title"></h5> 
                        <button type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Close">
                            <i data-feather="x-circle"></i>
                        </button>
                    </div>
                HTML;
        }

        echo <<<HTML
                                <div class="modal-body">
                                    <div class="prompt-content p-4">
                                    <img src="src/images/resc/warning.png" class="main-icon" alt="warning icon">
                                    <p class="fw-bold fs-3 spacing-4">{$subtitle}</p>
                                    <div>
                                        <p class="fw-medium spacing-5">
                                            {$message}
                                        </p>
                                    </div>
                                    <div class="d-flex flex-column w-100">
                                        <input type="text" id="confirmDeleteInput" class="form-control text-center" placeholder="Type here...">
                                        <span class="form-feedback text-center">Please type the words exactly as shown to proceed.</span>
                                    </div>

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


    static function getSuccessModal(
        $subtitle,
        $message,
        $hasCloseButton = true,
    ) {
        echo <<<HTML
                <div class="modal fade show success-modal" id="success-modal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
            HTML;

        if ($hasCloseButton) {
            echo <<<HTML
                        <div class="modal-header "><h5 class="modal-title"></h5> 
                            <button type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Close">
                                <i data-feather="x-circle"></i>
                            </button>
                        </div>
                    HTML;
        }



        echo <<<HTML
                    <div class="modal-body">
                        <div class="prompt-content p-4">
                        <div class="success-icon">
                            <img src="src/images/resc/check-animation.gif" class="main-icon" alt="warning icon">
                        </div>
                        <p class="fw-bold fs-3 spacing-4">{$subtitle}</p>
                        <p class="fw-medium spacing-5">
                            {$message}
                        </p>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            HTML;
    }
}
