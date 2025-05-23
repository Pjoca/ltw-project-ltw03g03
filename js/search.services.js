let loading = false;
let currentPage = 1; // Start on the first page
const itemsPerPage = 5; // This MUST match the $limit in action.search.services.php

function escapeHtml(text) {
  const div = document.createElement("div");
  div.textContent = text;
  return div.innerHTML;
}

function createServiceCard(service) {
  return `
    <article class="service-card">
      <div class="service-header">
        <div>
          <h3>${escapeHtml(service.poster_name ?? 'Unknown')}</h3>
          <span class="date">${new Date(service.created_at).toLocaleDateString()}</span>
        </div>
      </div>
      <h4>${escapeHtml(service.title)}</h4>
      <p><strong>Category:</strong> ${escapeHtml(service.category)}</p>
      <p><strong>Price:</strong> $${parseFloat(service.price).toFixed(2)}</p>
      <p><strong>Delivery time:</strong> ${escapeHtml(service.delivery_time)} days</p>
      <p>${escapeHtml(service.description)}</p>
      ${service.media ? `<div class="service-media"><img src="${escapeHtml(service.media)}" /></div>` : ''}
    </article>
  `;
}

function buildQueryParams() {
  const query = document.getElementById('search-query').value.trim();
  const category = document.getElementById('category').value;
  const price = document.getElementById('price').value;
  const delivery = document.getElementById('delivery').value;

  const params = new URLSearchParams();
  if (query) params.append('query', query);
  if (category) params.append('category', category);
  if (price) params.append('price', price);
  if (delivery) params.append('delivery', delivery);
  // Add pagination parameters
  params.append('page', currentPage.toString());
  params.append('limit', itemsPerPage.toString()); // Though PHP defines limit, good to be explicit

  return params.toString();
}

async function loadFilteredServices() {
  if (loading) return;
  loading = true;
  const noResultsMessage = document.getElementById('no-results-message');
  const searchResultsContainer = document.getElementById('search-results');
  const paginationControlsContainer = document.getElementById('pagination-controls');

  try {
    // Clear previous results and messages
    searchResultsContainer.innerHTML = '';
    noResultsMessage.style.display = 'none';
    paginationControlsContainer.innerHTML = ''; // Clear old pagination buttons

    const query = buildQueryParams();
    const res = await fetch(`/../actions/action.search.services.php?${query}`);
    const data = await res.json(); // Data will now be an object with services, totalResults, etc.

    if (data.services && data.services.length > 0) {
      data.services.forEach(service => {
        searchResultsContainer.insertAdjacentHTML('beforeend', createServiceCard(service));
      });
      // Render pagination controls after displaying services
      renderPaginationControls(data.totalResults, data.currentPage, data.itemsPerPage);
    } else {
      noResultsMessage.style.display = 'block';
    }
  } catch (e) {
    console.error("Failed to load services", e);
    // Optionally display an error message to the user
  } finally {
    loading = false;
  }
}

// Function to dynamically create and update pagination buttons
function renderPaginationControls(totalResults, currentPage, itemsPerPage) {
  const totalPages = Math.ceil(totalResults / itemsPerPage);
  const paginationControls = document.getElementById('pagination-controls');
  paginationControls.innerHTML = ''; // Clear previous controls

  if (totalPages <= 1) {
    return; // No need for pagination if only one page
  }

  // Create "Previous" button
  if (currentPage > 1) {
    const prevButton = document.createElement('button');
    prevButton.textContent = 'Previous';
    prevButton.classList.add('pagination-button'); // Add a class for styling
    prevButton.addEventListener('click', () => {
      currentPage--;
      loadFilteredServices(); // Load previous page
    });
    paginationControls.appendChild(prevButton);
  }

  // Create page number buttons (simplified: show all pages)
  
  for (let i = 1; i <= totalPages; i++) {
    const pageButton = document.createElement('button');
    pageButton.textContent = i;
    pageButton.classList.add('pagination-button'); // Add a class for styling
    if (i === currentPage) {
      pageButton.disabled = true; // Disable the current page button
      pageButton.classList.add('active'); // Add an active class for styling
    }
    pageButton.addEventListener('click', () => {
      currentPage = i;
      loadFilteredServices(); // Load the clicked page
    });
    paginationControls.appendChild(pageButton);
  }

  // Create "Next" button
  if (currentPage < totalPages) {
    const nextButton = document.createElement('button');
    nextButton.textContent = 'Next';
    nextButton.classList.add('pagination-button'); // Add a class for styling
    nextButton.addEventListener('click', () => {
      currentPage++;
      loadFilteredServices(); // Load next page
    });
    paginationControls.appendChild(nextButton);
  }
}

window.addEventListener('DOMContentLoaded', () => {
  // Event listener for the filter form submission
  document.getElementById('filter-form').addEventListener('submit', e => {
    e.preventDefault();
    currentPage = 1; // Always reset to the first page when a new search/filter is applied
    loadFilteredServices();
  });

  // Call loadFilteredServices on initial page load if you want to show results immediately
  // based on any URL parameters or default state
  // loadFilteredServices(); // Uncomment this line if you want to load results on DOMContentLoaded
});