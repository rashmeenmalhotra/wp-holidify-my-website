(function($) {
    'use strict';

    console.log("Holidify Admin JS Loaded");

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

    /**
     * Collect row data INCLUDING GREETING
     */
    function collectRowData($row) {
        return {
            id: $row.data('holidify-id'),
            name: $row.find('.holidify-name').val(),
            greeting: $row.find('.holidify-greeting').val(), 
            start_date: $row.find('.holidify-start-date').val(),
            end_date: $row.find('.holidify-end-date').val(),
            animation: $row.find('.holidify-animation-select').val(),
            icons: $row.find('.holidify-icon').map(function() {
                return $(this).val();
            }).get()
        };
    }

    /**
     * SAVE ROW — AJAX
     */
    function saveRow($row) {
        const data = collectRowData($row);

        console.log("Saving Holiday:", data);

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
                greeting: data.greeting,
                start_date: data.start_date,
                end_date: data.end_date,
                animation: data.animation,
                icons: data.icons
            }
        )
        .done(function(response) {
            console.log("Save response:", response);

            if (response && response.success) {
                window.location.reload();
            } else {
                alert(response?.data?.message || 'Error while saving.');
            }
        })
        .fail(function() {
            alert('AJAX error while saving holiday.');
        });
    }

    /**
     * ⭐ RESTORED: Save button click handler
     */
    $(document).on('click', '.holidify-save-holiday', function() {
        console.log("SAVE button clicked");
        const $row = $(this).closest('tr');
        saveRow($row);
    });

    /**
     * Delete row
     */
    function deleteRow($row) {
        const id = $row.data('holidify-id');
        if (!id) return;

        if (!confirm('Are you sure you want to delete this holiday?')) {
            return;
        }

        $.post(
            holidifyAdmin.ajax_url,
            {
                action: 'holidify_delete_holiday',
                nonce: holidifyAdmin.nonce,
                id: id
            }
        )
        .done(function(response) {
            if (response?.success) {
                $row.fadeOut(200, () => $row.remove());
            } else {
                alert(response?.data?.message || 'Error while deleting.');
            }
        })
        .fail(function() {
            alert('AJAX error while deleting holiday.');
        });
    }

    $(document).on('click', '.holidify-delete-holiday', function() {
        const $row = $(this).closest('tr');
        deleteRow($row);
    });

    /**
     * Pause mode
     */
    $(document).on('click', '.holidify-disable-all', function() {
        $.post(
            holidifyAdmin.ajax_url,
            {
                action: 'holidify_pause_holiday_mode',
                nonce: holidifyAdmin.nonce
            }
        ).done(() => window.location.reload());
    });

    /**
     * Resume mode
     */
    $(document).on('click', '.holidify-resume-holiday', function() {
        $.post(
            holidifyAdmin.ajax_url,
            {
                action: 'holidify_resume_holiday_mode',
                nonce: holidifyAdmin.nonce
            }
        ).done(() => window.location.reload());
    });

    /**
     * Add new row
     */
    $('#holidify-add-new-holiday').on('click', function() {
        const id = 'custom_' + Date.now();
        const $tbody = $('#holidify-table-body');

        const newRow = `
        <tr data-holidify-id="${id}">
            <td><input type="text" class="regular-text holidify-name"></td>

            <td><input type="text" class="regular-text holidify-greeting" placeholder="e.g. Merry Christmas 🎄"></td>

            <td><input type="date" class="small-text holidify-start-date"></td>
            <td><input type="date" class="small-text holidify-end-date"></td>

            <td class="holidify-icons-cell">
                <div class="holidify-icon-inputs">
                    <input type="text" class="small-text holidify-icon" maxlength="4">
                    <input type="text" class="small-text holidify-icon" maxlength="4">
                    <input type="text" class="small-text holidify-icon" maxlength="4">
                    <button type="button" class="button button-secondary holidify-icon-picker-btn">Icon Picker</button>
                </div>
            </td>

            <td>
                <select class="holidify-animation-select">
                    <option value="none">None</option>
                    <option value="snowflakes">Snowflakes</option>
                    <option value="bats">Bats</option>
                    <option value="leaves">Falling Leaves</option>
                    <option value="confetti">Confetti</option>
                    <option value="hearts">Floating Hearts</option>
                    <option value="fireworks">Fireworks</option>
                    <option value="maple">Maple Leaves</option>
                    <option value="eggs">Bouncing Eggs</option>
                </select>
            </td>

            <td>
                <button type="button" class="button button-primary holidify-save-holiday">Save</button>
                <button type="button" class="button button-link-delete holidify-delete-holiday">Delete</button>
            </td>
        </tr>`;

        $tbody.append(newRow);
    });

    /**
     * Modal events
     */
    $(document).on('click', '.holidify-icon-picker-btn', function() {
        const input = $(this).siblings('.holidify-icon').first().get(0);
        openIconModal(input);
    });

    $(document).on('click', '.holidify-modal-close, .holidify-modal-cancel, .holidify-modal-backdrop', function(e) {
        e.preventDefault();
        closeIconModal();
    });

    $(document).on('click', '.holidify-icon-option', function() {
        $('.holidify-icon-option').removeClass('is-selected');
        $(this).addClass('is-selected');

        if (currentIconInput) {
            currentIconInput.value = $(this).text();
        }

        closeIconModal();
    });

})(jQuery);
