<?php function draw_my_services_page() { ?>
    <div class="back-nav">
      <a href="/../pages/home.php" class="nav-button"> Home </a>
      <a href="/../pages/profile.php" class="nav-button"> Profile </a>
      <a href="/../pages/my.search.php" class="nav-button"> Search </a>
      <a href="/../pages/messages.php" class="nav-button"> Messages </a>
      <a href="logout.php"class="nav-button"> Logout</a>
    </div>

    <section class="homepage-banner">
      <h2>My Services</h2>
      <p>Here are the services you've listed.</p>
      <a href="create.service.php" class="create-button">+ Offer a Service</a>
    </section>

    <section id="my-service-list" class="service-list">
      <!-- User's services will load here -->
    </section>

    <div id="loader" style="display: none; text-align: center; padding: 1rem;">
      <div class="spinner"></div>
    </div>
  </div>

  <script>
    const userId = <?= json_encode($_SESSION['user_id']) ?>;
  </script>
  <script src="/../js/load.services.js" defer></script>
<?php } ?>


<?php function draw_create_service() { ?>

    <div class="back-nav">
      <a href="/../pages/home.php" class="nav-button"> Home </a>
      <a href="/../pages/profile.php" class="nav-button"> Profile </a>
      <a href="/../pages/my.services.php" class="nav-button"> My Services </a>
      <a href="/../pages/search.php" class="nav-button"> Search </a>
      <a href="/../pages/messages.php" class="nav-button"> Messages </a>
      <a href="logout.php"class="nav-button"> Logout</a>
    </div>

<form action="../actions/action.submit.service.php" method="POST" enctype="multipart/form-data">
  <div>
    <label for="title">Title</label>
    <input type="text" name="title" id="title" required>
  </div>

  <div>
    <label for="description">Description</label>
    <textarea name="description" id="description" required rows="6"></textarea>
  </div>

  <div>
    <div>
      <label for="price">Price</label>
      <input type="number" name="price" id="price" min="0.01" step="0.01" required placeholder="-- $">
    </div>

    <div>
      <label for="delivery_time">Delivery Time (days)</label>
      <input type="number" name="delivery_time" id="delivery_time" min="1" required>
    </div>

    <div>
      <label for="media">Media (optional)</label>
      <input type="file" name="media" id="media" accept="image/*">
    </div>

    <div>
      <label for="category_id">Category</label>
      <select name="category_id" id="category_id" required>
        <option value="" disabled selected>Select a category</option>
        <option value="1">Web Development</option>
        <option value="2">Graphic Design</option>
        <option value="3">Writing</option>
        <option value="4">Marketing</option>
        <option value="5">Photography</option>
      </select>
    </div>
  </div>

  <button type="submit" class="submit-button">Submit Service</button>
</form>

<?php } ?>

<?php function draw_search(string $selectedQuery, string $selectedCategory, ?float $selectedPrice, ?int $selectedDelivery, array $categories, array $services) { ?>

    <div class="back-nav">
      <a href="/../pages/home.php" class="nav-button"> Home </a>
      <a href="/../pages/profile.php" class="nav-button"> Profile </a>
      <a href="/../pages/my.services.php" class="nav-button"> My Services </a>
      <a href="/../pages/messages.php" class="nav-button"> Messages </a>
      <a href="logout.php"class="nav-button"> Logout</a>
    </div>
  
    <section class="search-filters-section">
      <form id="filter-form" action="search.php" method="GET" class="search-form">

        <div class="form-group">
          <label for="query">Search:</label>
          <input type="text" name="query" id="search-query" placeholder="e.g. photography, website, Bob Smith" value="<?= htmlspecialchars($selectedQuery) ?>">
        </div>

        <div class="form-group">
          <label for="category">Category:</label>
          <select name="category" id="category" class="filter-select">
            <option value="">All</option>
            <?php foreach ($categories as $c): ?>
              <option value="<?= htmlspecialchars($c->name) ?>" <?= ($selectedCategory === $c->name) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c->name) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="price">Max Price:</label>
          <input type="number" name="price" id="price" min="0" placeholder="e.g. 100" class="filter-input" value="<?= htmlspecialchars((string)$selectedPrice) ?>">
        </div>

        <div class="form-group">
          <label for="delivery">Max Delivery (days):</label>
          <input type="number" name="delivery" id="delivery" min="1" placeholder="e.g. 7" class="filter-input" value="<?= htmlspecialchars((string)$selectedDelivery) ?>">
        </div>

        <button type="submit" class="filter-button">Apply Filters</button>
      </form>
    </section>
    
<section id="search-results" class="service-list">
  <!-- Filtered services will be inserted here -->
</section>

<div id="loader">
  <div class="spinner"></div>
</div>

<script src="/../js/search.services.js" defer></script>

<?php } ?>
