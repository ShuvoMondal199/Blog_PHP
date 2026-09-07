<form action="" method="POST">
<div class="">
    <label for="name">Name: </label>
    <input type="text" name="name" placeholder="Enter Your Name" required>
</div>
<div class="">
    <label for="email">Email: </label>
    <input type="email" name="email" placeholder="Enter Your Email" required>
</div>
<div class="">
    <label for="password">Password: </label>
    <input type="password" name="password" placeholder="Enter Your Password" required>
</div>
<div class="">
    <label for="role">Role: </label>
    <select name="role" id="role">
        <option value="" selected>Select Role</option>
        <option value="Admin">Admin</option>
        <option value="User">User</option>
        <option value="Editor">Editor</option>
    </select>
</div>

<button type="submit">Submit</button>

</form>