let offset = 0;
const limit = 3;
let loading = false;
let hasSearched = false;
let scrollTimeout = null;

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
  params.append('offset', offset);

  return params.toString();
}

async function loadFilteredServices(reset = false) {
  if (loading) return;
  loading = true;
  document.getElementById('loader').style.display = 'block';
  const noResultsMessage = document.getElementById('no-results-message');

  try {
    if (reset) {
      offset = 0;
      document.getElementById('search-results').innerHTML = '';
      noResultsMessage.style.display = 'none';
    }

    const query = buildQueryParams();
    const res = await fetch(`/../actions/action.load.services.php?${query}`);
    const data = await res.json();

    const container = document.getElementById('search-results');

    if (data.length === 0) {
      if (reset) {
        noResultsMessage.style.display = 'block';
      }
      window.removeEventListener('scroll', handleScroll);
    } else {
      data.forEach(service => {
        container.insertAdjacentHTML('beforeend', createServiceCard(service));
      });
      offset += limit;
    }
  } catch (e) {
    console.error("Failed to load services", e);
  } finally {
    loading = false;
    document.getElementById('loader').style.display = 'none';
  }
}


function handleScroll() {
  if (!hasSearched) return;
  if (scrollTimeout) return;

  scrollTimeout = setTimeout(() => {
    if (window.scrollY + window.innerHeight >= document.body.offsetHeight - 20) {
      loadFilteredServices();
    }
    scrollTimeout = null;
  }, 500);
}

window.addEventListener('DOMContentLoaded', () => {
  document.getElementById('filter-form').addEventListener('submit', e => {
    e.preventDefault();
    hasSearched = true;
    loadFilteredServices(true);
  });

  // Don't trigger search on load
  // Only allow scroll after a real search
});

window.addEventListener('scroll', handleScroll);
