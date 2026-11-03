import './bootstrap';
import uiDropdown from './ui/dropdown.js';

const registerUiDropdown = (Alpine) => {
    if (!Alpine || Alpine.__uiDropdownRegistered) return;
    Alpine.data('uiDropdown', uiDropdown);
    Alpine.__uiDropdownRegistered = true;
};

document.addEventListener('alpine:init', () => registerUiDropdown(window.Alpine));

if (window.Alpine) registerUiDropdown(window.Alpine);
