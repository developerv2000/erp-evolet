@props(['formAction', 'recordId'])

<x-misc.button
    style="transparent"
    class="td__restore"
    icon="settings_backup_restore"
    title="Восстановить"
    data-click-action="show-target-restore-modal"
    :data-form-action="$formAction"
    :data-target-id="$recordId" />
