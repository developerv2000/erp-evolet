@props(['formAction', 'recordId'])

<x-misc.button
    style="transparent"
    class="td__restore"
    icon="settings_backup_restore"
    title="Restore"
    data-click-action="show-target-restore-modal"
    :data-form-action="$formAction"
    :data-target-id="$recordId" />
