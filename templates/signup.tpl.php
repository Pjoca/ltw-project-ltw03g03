<form action="../actions/action_signup.php" method="post">
    <label>Name: <input type="text" name="name" required></label><br>
    <label>Username: <input type="text" name="username" required></label><br>
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <label>Role: 
      <select name="role" required>
        <option value="client">Client</option>
        <option value="freelancer">Freelancer</option>
      </select></label><br>
  <button type="submit">Sign Up</button>
</form>
