//initialize flowbite

import 'flowbite';
// Import jQuery first
import $ from 'jquery';


document.addEventListener('livewire:navigated', () => {
    // Re-initialize all Flowbite components.
    if (typeof initFlowbite === 'function') {
        initFlowbite();
    }

});
window.addEventListener('page-reload', function(e) {
    window.location.reload();
});



