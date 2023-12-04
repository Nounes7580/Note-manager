<nav class="navbar fixed-top">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="#">MyApp</a>
    <div class="offcanvas offcanvas-start custom-offcanvas" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header">
        <!-- Use the Bootstrap class for text color -->
        <h5 class="offcanvas-title text-warning" id="offcanvasNavbarLabel">NoteApp</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
          <li class="nav-item active">
            <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../index.php?controller=main&action=logout">Logout</a>
          </li>
          <!-- Add more menu items here as needed -->
        </ul>
        <!-- Optional: Add a search form or other elements here -->
      </div>
    </div>
  </div>
</nav>

<!-- Add custom CSS to style the shorter offcanvas menu -->
<style>
  .custom-offcanvas {
    max-width: 50%; /* Adjust the width to your desired value */
  }
</style>
