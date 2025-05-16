let offset = 0;
const limit = 3;
let loading = false;

function createServiceCard(service) {
  return `
    <article class="service-card">
      <div class="service-header">
        <div>
          <h3>${escapeHtml(service.poster_name)}</h3>
          <span class="date">${new Date(service.created_at).toLocaleDateString()}</span>
        </div>
      </div>
      <h4>${escapeHtml(service.title)}</h4>
      <p><strong>Category:</strong> ${escapeHtml(service.category)}</p>
      <p>${escapeHtml(service.description)}</p>
      <p><strong>Price:</strong> $${parseFloat(service.price).toFixed(2)}</p>
      <p><strong>Delivery time:</strong> ${escapeHtml(service.delivery_time)} days</p>
      ${service.media ? `<div class="service-media"><img src="/media/${escapeHtml(service.media)}" style="max-width:300px;" /></div>` : ''}
    </article>
  `;
}

async function loadMoreServices() {
  if (loading) return;
  loading = true;
  document.getElementById('loader').style.display = 'block';

  try {
    const res = await fetch(`/../actions/action_load_services.php?offset=${offset}`);
    const data = await res.json();

    if (data.length === 0) {
      window.removeEventListener('scroll', handleScroll);
    }

    const container = document.getElementById('service-list');
    data.forEach(service => {
      container.insertAdjacentHTML('beforeend', createServiceCard(service));
    });

    offset += limit;
  } catch (e) {
    console.error("Failed to load services", e);
  } finally {
    loading = false;
    document.getElementById('loader').style.display = 'none';
  }
}

let scrollTimeout = null;

function handleScroll() {
  if (scrollTimeout) return;
  scrollTimeout = setTimeout(() => {
    const scrollY = window.scrollY;
    const innerHeight = window.innerHeight;
    const offsetHeight = document.body.offsetHeight;

    if (scrollY + innerHeight >= offsetHeight - 20) {
      loadMoreServices();
    }

    scrollTimeout = null;
  }, 1000); // wait 1000ms between scroll checks
}


window.addEventListener('scroll', handleScroll);
window.addEventListener('DOMContentLoaded', loadMoreServices);

// Optional: basic XSS protection
function escapeHtml(text) {
  const div = document.createElement("div");
  div.textContent = text;
  return div.innerHTML;
}
