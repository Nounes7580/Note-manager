<!-- views/signup_form.php -->
<form action="/index.php?controller=Signup&action=processSignup" method="post">
    <!-- Form fields for username, email, password, etc. -->
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>

    <!-- Add more fields as needed -->

    <input type="submit" value="Sign Up">
</form>