(function($) {
    'use strict';

    let currentIconInput = null;

    function openIconModal(inputEl) {
        currentIconInput = inputEl;
        $('#holidify-icon-modal').show();
        $('.holidify-modal-backdrop').show();
    }

    function closeIconModal() {
        $('#holidify-icon-modal').hide();
        $('.holidify-modal-backdrop').hide();
        currentIconInput = null;
        $('.holidify-icon-option').removeClass('is-selected');
    }

    function collectRowData($row) {
        const id = $row.data('holidify-id');
        const name = $row.find('.holidify-name').val();
        const start = $row.find('.holidify-start-date').val();
        const end = $row.find('.holidify-end-date').val();
        const animation = $row.find('.holidify-animation-select').val();

        const icons = [];
        $row.find('.holidify-icon').each(function() {
            icons.push($(this).val());
        });

        return {
            id: id,
            name: name,
            start_date: start,
            end_date: end,
            animation: animation,
            icons: icons
        };
    }

    function saveRow($row) {
        const data = collectRowData($row);

        if (!data.name) {
            alert('Please enter a holiday name.');
            return;
        }

        $.post(
            holidifyAdmin.ajax_url,
            {
                action: 'holidify_save_holiday',
                nonce: holidifyAdmin.nonce,
                id: data.id,
                name: data.name,
                start_date: data.start_date,
                end_date: data.end_date,
                animation: data.animation,
                icons: data.icons
            }
        ).done(function(response) {
            if (response && response.success) {
                // Optionally show a nicer notice instead of alert
                // For now keep it simple
                // alert(response.data.message || 'Saved.');
                // Reload to refresh in case of newly added row
                window.location.reload();
            } else {
                alert((response && response.data && response.data.message) || 'Error while saving.');
            }
        }).fail(function() {
            alert('Ajax error while saving holiday.');
        });
    }

    function deleteRow($row) {
        const id = $row.data('holidify-id');
        if (!id) return;

        if (!window.confirm('Are you sure you want to delete this holiday?')) {
            return;
        }

        $.post(
            holidifyAdmin.ajax_url,
            {
                action: 'holidify_delete_holiday',
                nonce: holidifyAdmin.nonce,
                id: id
            }
        ).done(function(response) {
            if (response && response.success) {
                $row.fadeOut(200, function() {
                    $(this).remove();
                });
            } else {
                alert((response && response.data && response.data.message) || 'Error while deleting.');
            }
        }).fail(function() {
            alert('Ajax error while deleting holiday.');
        });
    }

    function setActiveHoliday(id) {
        $.post(
            holidifyAdmin.ajax_url,
            {
                action: 'holidify_set_active_holiday',
                nonce: holidifyAdmin.nonce,
                id: id
            }
        ).done(function(response) {
            if (response && response.success) {
                window.location.reload();
            } else {
                alert((response && response.data && response.data.message) || 'Error while setting active holiday.');
            }
        }).fail(function() {
            alert('Ajax error while setting active holiday.');
        });
    }

    function createNewRow() {
        const id = 'custom_' + Date.now();
        const $tbody = $('#holidify-table-body');

        const $row = $('<tr/>', {
            'data-holidify-id': id
        });

        const $activeTd = $('<td/>', { 'class': 'column-active' }).append(
            $('<input/>', {
                type: 'radio',
                name: 'holidify_active',
                'class': 'holidify-active-radio'
            })
        );

        const $nameTd = $('<td/>').append(
            $('<input/>', {
                type: 'text',
                'class': 'regular-text holidify-name',
                value: ''
            })
        );

        const $startTd = $('<td/>').append(
            $('<input/>', {
                type: 'date',
                'class': 'small-text holidify-start-date',
                value: ''
            })
        );

        const $endTd = $('<td/>').append(
            $('<input/>', {
                type: 'date',
                'class': 'small-text holidify-end-date',
                value: ''
            })
        );

        const $iconsTd = $('<td/>', { 'class': 'holidify-icons-cell' });
        const $iconWrapper = $('<div/>', { 'class': 'holidify-icon-inputs' });

        for (let i = 0; i < 3; i++) {
            $iconWrapper.append(
                $('<input/>', {
                    type: 'text',
                    'class': 'small-text holidify-icon',
                    maxlength: 4,
                    value: ''
                })
            );
        }

        $iconWrapper.append(
            $('<button/>', {
                type: 'button',
                'class': 'button button-secondary holidify-icon-picker-btn',
                text: 'Icon Picker'
            })
        );

        $iconsTd.append($iconWrapper);

        const $animationTd = $('<td/>');
        const $select = $('<select/>', { 'class': 'holidify-animation-select' });

        const animations = {
            none: 'None',
            snowflakes: 'Snowflakes',
            bats: 'Bats',
            leaves: 'Falling Leaves',
            confetti: 'Confetti',
            hearts: 'Floating Hearts',
            fireworks: 'Fireworks',
            maple: 'Maple Leaves',
            eggs: 'Bouncing Eggs'
        };

        $.each(animations, function(value, label) {
            $select.append($('<option/>', {
                value: value,
                text: label
            }));
        });

        $animationTd.append($select);

        const $actionsTd = $('<td/>');
        $actionsTd.append(
            $('<button/>', {
                type: 'button',
                'class': 'button button-primary holidify-save-holiday',
                text: 'Save'
            })
        );
        $actionsTd.append(' ');
        $actionsTd.append(
            $('<button/>', {
                type: 'button',
                'class': 'button button-link-delete holidify-delete-holiday',
                text: 'Delete'
            })
        );

        $row.append($activeTd, $nameTd, $startTd, $endTd, $iconsTd, $animationTd, $actionsTd);
        $tbody.append($row);
        return $row;
    }

    $(function() {

        // Save row
        $(document).on('click', '.holidify-save-holiday', function() {
            const $row = $(this).closest('tr');
            saveRow($row);
        });

        // Delete row
        $(document).on('click', '.holidify-delete-holiday', function() {
            const $row = $(this).closest('tr');
            deleteRow($row);
        });

        // Set active holiday
        $(document).on('change', '.holidify-active-radio', function() {
            const $row = $(this).closest('tr');
            const id = $row.data('holidify-id');
            if (id) {
                setActiveHoliday(id);
            }
        });

        // Disable all holidays
        $('.holidify-disable-all').on('click', function() {
            setActiveHoliday('');
        });

        // Add new holiday row
        $('#holidify-add-new-holiday').on('click', function() {
            createNewRow();
        });

        // Open icon modal
        $(document).on('click', '.holidify-icon-picker-btn', function() {
            const $input = $(this).siblings('.holidify-icon').first();
            openIconModal($input.get(0));
        });

        // Close modal
        $(document).on('click', '.holidify-modal-close, .holidify-modal-cancel, .holidify-modal-backdrop', function(e) {
            e.preventDefault();
            closeIconModal();
        });

        // Select icon
        $(document).on('click', '.holidify-icon-option', function() {
            $('.holidify-icon-option').removeClass('is-selected');
            $(this).addClass('is-selected');

            if (currentIconInput) {
                currentIconInput.value = $(this).text();
            }

            // Close immediately after choosing
            closeIconModal();
        });
    });

})(jQuery);
