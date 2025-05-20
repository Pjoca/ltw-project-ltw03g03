let offset = 0;
const limit = 3;
let loading = false;
let scrollTimeout = null;

function escapeHtml(txt) {
  const div = document.createElement('div');
  div.textContent = txt;
  return div.innerHTML;
}

function createServiceCard(s, postedBy = null) {
  const isOwner = postedBy === 'You';
  return `
    <article class="service-card">
      <div class="service-header">
        <div>
          <h3>${escapeHtml(postedBy ?? s.poster_name ?? 'You')}</h3>
          <span class="date">${new Date(s.created_at).toLocaleDateString()}</span>
        </div>
      </div>
      <h4>${escapeHtml(s.title)}</h4>
      <p><strong>Category:</strong> ${escapeHtml(s.category)}</p>
      <p>${escapeHtml(s.description)}</p>
      <p><strong>Price:</strong> $${parseFloat(s.price).toFixed(2)}</p>
      <p><strong>Delivery time:</strong> ${escapeHtml(s.delivery_time)} days</p>
      ${s.media ? `<div class="service-media"><img src="${escapeHtml(s.media)}" style="max-width:300px;" /></div>` : ''}
      ${isOwner ? `
        <div class="service-actions">
          <button class="edit-btn"    onclick="editService('${s.title}')">Edit</button>
          <button class="delete-btn"  onclick="deleteService('${s.title}')">Delete</button>
        </div>` : ''}
    </article>`;
}

function getFilterValues() {
  return {
    category : document.getElementById('category')?.value || '',
    price    : document.getElementById('price')?.value    || '',
    delivery : document.getElementById('delivery')?.value || ''
  };
}

function getCurrentPageConfig() {
  const isMyServicesPage = window.location.pathname.includes('my.services.php');
  return {
    endpoint: isMyServicesPage
      ? '/../actions/action.load.my.services.php'
      : '/../actions/action.load.services.php',
    posterName: isMyServicesPage ? 'You' : null
  };
}

async function loadServices(url, containerId, postedBy = null, filters = {}) {
  if (loading) return;
  loading = true;
  document.getElementById('loader').style.display = 'block';

  const query = new URLSearchParams({ offset, ...filters }).toString();

  try {
    const res = await fetch(`${url}?${query}`);
    const data = await res.json();

    if (data.length === 0) {
      window.removeEventListener('scroll', handleScroll);
    }

    const container = document.getElementById(containerId);
    data.forEach(s => container.insertAdjacentHTML('beforeend', createServiceCard(s, postedBy)));
    offset += limit;

  } catch (err) {
    console.error('Failed to load services', err);
  } finally {
    loading = false;
    document.getElementById('loader').style.display = 'none';
  }
}

function handleScroll() {
  if (scrollTimeout) return;
  scrollTimeout = setTimeout(() => {
    if (window.scrollY + window.innerHeight >= document.body.offsetHeight - 20) {
      const { endpoint, posterName } = getCurrentPageConfig();
      loadServices(endpoint, 'service-list', posterName, getFilterValues());
    }
    scrollTimeout = null;
  }, 800);
}

window.addEventListener('scroll', handleScroll);

window.addEventListener('DOMContentLoaded', () => {
  const { endpoint, posterName } = getCurrentPageConfig();
  loadServices(endpoint, 'service-list', posterName, getFilterValues());

  const form = document.getElementById('filter-form');
  form?.addEventListener('submit', e => {
    e.preventDefault();
    offset = 0;
    document.getElementById('service-list').innerHTML = '';
    loadServices(endpoint, 'service-list', posterName, getFilterValues());
  });
});
