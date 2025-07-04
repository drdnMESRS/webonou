//initialize flowbite

import 'flowbite';

document.addEventListener('livewire:navigated', () => {
    // Re-initialize all Flowbite components.
    if (typeof initFlowbite === 'function') {
        initFlowbite();
    }
});
window.addEventListener('page-reload', function(e) {
    window.location.reload();
});
