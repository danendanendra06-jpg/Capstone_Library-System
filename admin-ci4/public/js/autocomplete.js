document.addEventListener('DOMContentLoaded', function() {
    const searchInputs = document.querySelectorAll('input[name="search"]');

    searchInputs.forEach(function(input) {
        // Create wrapper for dropdown positioning if not already in one
        input.setAttribute('autocomplete', 'off'); // Disable default browser autocomplete
        
        let dropdown = document.createElement('div');
        dropdown.className = 'dropdown-menu w-100 shadow-sm';
        dropdown.style.position = 'absolute';
        dropdown.style.top = '100%';
        dropdown.style.left = '0';
        dropdown.style.zIndex = '1000';
        dropdown.style.maxHeight = '300px';
        dropdown.style.overflowY = 'auto';
        dropdown.style.marginTop = '0.25rem';
        
        // Wrap input in relative container
        const wrapper = document.createElement('div');
        wrapper.className = 'position-relative w-100';
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);
        wrapper.appendChild(dropdown);

        let debounceTimer;

        input.addEventListener('keyup', function(e) {
            clearTimeout(debounceTimer);
            const query = this.value.trim();

            if (query.length < 1) {
                dropdown.classList.remove('show');
                return;
            }

            debounceTimer = setTimeout(function() {
                // Fetch data using AJAX
                const url = new URL(window.location.href);
                url.searchParams.set('search', query);
                url.searchParams.set('ajax', '1');

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        dropdown.innerHTML = ''; // Clear previous
                        if (data && data.length > 0) {
                            data.forEach(item => {
                                let a = document.createElement('a');
                                a.className = 'dropdown-item';
                                a.href = '#';
                                a.style.cursor = 'pointer';
                                a.textContent = item;
                                a.addEventListener('click', function(evt) {
                                    evt.preventDefault();
                                    input.value = item;
                                    dropdown.classList.remove('show');
                                    // Submit the form
                                    input.closest('form').submit();
                                });
                                dropdown.appendChild(a);
                            });
                            dropdown.classList.add('show');
                        } else {
                            let span = document.createElement('span');
                            span.className = 'dropdown-item text-muted';
                            span.textContent = 'No suggestions found';
                            dropdown.appendChild(span);
                            dropdown.classList.add('show');
                        }
                    })
                    .catch(err => console.error('Autocomplete Error:', err));
            }, 300); // 300ms debounce
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!wrapper.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
    });
});
