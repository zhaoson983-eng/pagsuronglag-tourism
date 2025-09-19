@props(['name', 'required' => false, 'value' => ''])

<div class="location-select-container">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
        {{ $slot }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    
    <div class="relative">
        <!-- Search Input -->
        <input type="text" id="{{ $name }}_search" 
               class="appearance-none relative block w-full px-3 py-1.5 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-900 text-sm"
               placeholder="Type to search or enter custom address..."
               autocomplete="off"
               oninput="filterLocations(this, '{{ $name }}')"
               onfocus="showDropdown('{{ $name }}')"
               onblur="hideDropdown('{{ $name }}')">
        
        <!-- Hidden input for form submission -->
        <input type="hidden" id="{{ $name }}" name="{{ $name }}" value="{{ $value }}" {{ $required ? 'required' : '' }}>
        
        <!-- Dropdown List -->
        <div id="{{ $name }}_dropdown" class="absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1 max-h-60 overflow-y-auto hidden">
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'Cabotonan, Lagonoy, Camarines Sur')">Cabotonan, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'Cabungahan, Lagonoy, Camarines Sur')">Cabungahan, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'Daligan, Lagonoy, Camarines Sur')">Daligan, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'Guinaban, Lagonoy, Camarines Sur')">Guinaban, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'Guijalo, Lagonoy, Camarines Sur')">Guijalo, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'Hiwacloy, Lagonoy, Camarines Sur')">Hiwacloy, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'Lungib, Lagonoy, Camarines Sur')">Lungib, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'Oma-oma, Lagonoy, Camarines Sur')">Oma-oma, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'Pag-oring Nuevo, Lagonoy, Camarines Sur')">Pag-oring Nuevo, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'Pag-oring Viejo, Lagonoy, Camarines Sur')">Pag-oring Viejo, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'Pinamihagan, Lagonoy, Camarines Sur')">Pinamihagan, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'Poblacion Zone 1, Lagonoy, Camarines Sur')">Poblacion Zone 1, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'Poblacion Zone 2, Lagonoy, Camarines Sur')">Poblacion Zone 2, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'Poblacion Zone 3, Lagonoy, Camarines Sur')">Poblacion Zone 3, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'Poblacion Zone 4, Lagonoy, Camarines Sur')">Poblacion Zone 4, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'Poblacion Zone 5, Lagonoy, Camarines Sur')">Poblacion Zone 5, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'San Antonio, Lagonoy, Camarines Sur')">San Antonio, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'San Francisco, Lagonoy, Camarines Sur')">San Francisco, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'San Isidro, Lagonoy, Camarines Sur')">San Isidro, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'San Jose, Lagonoy, Camarines Sur')">San Jose, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'San Juan, Lagonoy, Camarines Sur')">San Juan, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'San Miguel, Lagonoy, Camarines Sur')">San Miguel, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'San Rafael, Lagonoy, Camarines Sur')">San Rafael, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'San Ramon, Lagonoy, Camarines Sur')">San Ramon, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'San Roque, Lagonoy, Camarines Sur')">San Roque, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'San Sebastian, Lagonoy, Camarines Sur')">San Sebastian, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'San Vicente, Lagonoy, Camarines Sur')">San Vicente, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'Santa Catalina, Lagonoy, Camarines Sur')">Santa Catalina, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'Santa Cruz, Lagonoy, Camarines Sur')">Santa Cruz, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'Santa Lourdes, Lagonoy, Camarines Sur')">Santa Lourdes, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'Santa Maria, Lagonoy, Camarines Sur')">Santa Maria, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'Santa Maria Dos, Lagonoy, Camarines Sur')">Santa Maria Dos, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'Santa Teresita, Lagonoy, Camarines Sur')">Santa Teresita, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'Sagrada, Lagonoy, Camarines Sur')">Sagrada, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'Sto. Niño, Lagonoy, Camarines Sur')">Sto. Niño, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'Talisay, Lagonoy, Camarines Sur')">Talisay, Lagonoy, Camarines Sur</div>
            <div class="location-option px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm" onclick="selectLocation('{{ $name }}', 'Tamat, Lagonoy, Camarines Sur')">Tamat, Lagonoy, Camarines Sur</div>
        </div>
        
        <!-- Chevron down icon -->
        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </div>
    </div>
</div>

<script>
function filterLocations(input, fieldName) {
    const dropdown = document.getElementById(fieldName + '_dropdown');
    const options = dropdown.querySelectorAll('.location-option');
    const searchTerm = input.value.toLowerCase();
    const hiddenInput = document.getElementById(fieldName);
    
    // Show dropdown when typing
    dropdown.classList.remove('hidden');
    
    let hasVisibleOptions = false;
    
    options.forEach(option => {
        const text = option.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            option.style.display = 'block';
            hasVisibleOptions = true;
        } else {
            option.style.display = 'none';
        }
    });
    
    // If no matching options and user typed something, allow custom entry
    if (!hasVisibleOptions && searchTerm.length > 0) {
        hiddenInput.value = input.value; // Use typed value as custom address
    } else if (searchTerm.length === 0) {
        hiddenInput.value = ''; // Clear if empty
    }
}

function selectLocation(fieldName, location) {
    const searchInput = document.getElementById(fieldName + '_search');
    const hiddenInput = document.getElementById(fieldName);
    const dropdown = document.getElementById(fieldName + '_dropdown');
    
    // Auto-fill the address bar with selected location
    searchInput.value = location;
    hiddenInput.value = location;
    dropdown.classList.add('hidden');
    
    // Trigger change event for any form validation
    searchInput.dispatchEvent(new Event('change', { bubbles: true }));
    hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
}

function showDropdown(fieldName) {
    const dropdown = document.getElementById(fieldName + '_dropdown');
    dropdown.classList.remove('hidden');
}

function hideDropdown(fieldName) {
    // Delay hiding to allow click events on options
    setTimeout(() => {
        const dropdown = document.getElementById(fieldName + '_dropdown');
        dropdown.classList.add('hidden');
    }, 200);
}

// Handle mouse down events on dropdown options to prevent blur
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('mousedown', function(e) {
        if (e.target.classList.contains('location-option')) {
            e.preventDefault(); // Prevent blur event
        }
    });
});

// Initialize with existing value
document.addEventListener('DOMContentLoaded', function() {
    const containers = document.querySelectorAll('.location-select-container');
    containers.forEach(container => {
        const hiddenInput = container.querySelector('input[type="hidden"]');
        const searchInput = container.querySelector('input[type="text"]');
        
        if (hiddenInput && hiddenInput.value && searchInput) {
            searchInput.value = hiddenInput.value;
        }
    });
});
</script>
