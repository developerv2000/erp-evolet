document.addEventListener('DOMContentLoaded', function () {

    // Load state from sessionStorage
    const hiddenGroups = JSON.parse(sessionStorage.getItem('hiddenGroups') || '[]');

    // Apply hidden state to saved groups
    hiddenGroups.forEach(groupId => {
        document.querySelectorAll('.group-' + groupId)
            .forEach(col => col.classList.add('hidden'));
    });

    document.querySelectorAll('.group-toggle').forEach(toggle => {
        toggle.addEventListener('click', function () {
            console.log('works');

            const groupId = this.dataset.group;

            document.querySelectorAll('.group-' + groupId)
                .forEach(col => col.classList.toggle('hidden'));

            // Update state in sessionStorage
            const isHidden = document.querySelector('.group-' + groupId).classList.contains('hidden');

            let state = JSON.parse(sessionStorage.getItem('hiddenGroups') || '[]');

            if (isHidden) {
                if (!state.includes(groupId)) state.push(groupId);
            } else {
                state = state.filter(id => id !== groupId);
            }

            sessionStorage.setItem('hiddenGroups', JSON.stringify(state));
        });
    });

});
