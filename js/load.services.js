let offset = 0;
const limit = 3;
let loading = false;

function escapeHtml(text) {
  const div = document.createElement("div");
  div.textContent = text;
  return div.innerHTML;
}

function createServiceCard(service, postedBy = null) {
  return `
    <article class="service-card">
      <div class="service-header">
        <div>
          <h3>${escapeHtml(postedBy ?? service.poster_name ?? 'You')}</h3>
          <span class="date">${new Date(service.created_at).toLocaleDateString()}</span>
        </div>
      </div>
      <h4>${escapeHtml(service.title)}</h4>
      <p><strong>Category:</strong> ${escapeHtml(service.category)}</p>
      <p>${escapeHtml(service.description)}</p>
      <p><strong>Price:</strong> $${parseFloat(service.price).toFixed(2)}</p>
      <p><strong>Delivery time:</strong> ${escapeHtml(service.delivery_time)} days</p>
      ${service.media ? `<div class="service-media"><img src="https://picsum.photos/200?service=${encodeURIComponent(service.title)}" style="max-width:300px;" /></div>` : ''}
    </article>
  `;
}

async function loadServices(url, containerId, postedBy = null) {
  if (loading) return;
  loading = true;
  document.getElementById('loader').style.display = 'block';

  try {
    const res = await fetch(`${url}?offset=${offset}`);
    const data = await res.json();

    if (data.length === 0) {
      window.removeEventListener('scroll', handleScroll);
    }

    const container = document.getElementById(containerId);
    data.forEach(service => {
      container.insertAdjacentHTML('beforeend', createServiceCard(service, postedBy));
    });

    offset += limit;
  } catch (e) {
    console.error("Failed to load services", e);
  } finally {
    loading = false;
    document.getElementById('loader').style.display = 'none';
  }
}

function handleScroll() {
  if (scrollTimeout) return;
  scrollTimeout = setTimeout(() => {
    const scrollY = window.scrollY;
    const innerHeight = window.innerHeight;
    const offsetHeight = document.body.offsetHeight;

    if (scrollY + innerHeight >= offsetHeight - 20) {
      if (document.getElementById('service-list')) {
        loadServices('/../actions/action.load.services.php', 'service-list');
      } else if (document.getElementById('my-service-list')) {
        loadServices('/../actions/action.load.my.services.php', 'my-service-list', 'You');
      }
    }
    scrollTimeout = null;
  }, 1000);
}

let scrollTimeout = null;

window.addEventListener('scroll', handleScroll);
window.addEventListener('DOMContentLoaded', () => {
  if (document.getElementById('service-list')) {
    loadServices('/../actions/action.load.services.php', 'service-list');
  } else if (document.getElementById('my-service-list')) {
    loadServices('/../actions/action.load.my.services.php', 'my-service-list', 'You');
  }
});
