<?php function draw_contact_freelancer(array $service) { ?>
  <section class="contact-freelancer">
    <h2>Contact Freelancer</h2>
    <p>You are contacting about: <strong><?= htmlspecialchars($service['title']) ?></strong></p>

    <form action="/../actions/action.send.message.php" method="POST">
      <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
      <input type="hidden" name="receiver_id" value="<?= $service['user_id'] ?>">

      <div>
        <label for="message">Message:</label>
        <textarea name="message" id="message" rows="6" required></textarea>
      </div>

      <div>
        <label for="proposed_price">Proposed Price (optional):</label>
        <input type="number" name="proposed_price" step="0.01">
      </div>

      <div>
        <label for="delivery_days">Delivery Time (days, optional):</label>
        <input type="number" name="delivery_days" min="1">
      </div>

      <button type="submit">Send</button>
    </form>
  </section>
<?php } ?>
