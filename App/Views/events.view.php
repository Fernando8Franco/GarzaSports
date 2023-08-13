<div class="container mt-5">
    <table class="table" id="data-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
        </tr>
      </thead>
      <tbody hx-get="/data-source.php">
        <!-- Data will be loaded here -->
      </tbody>
    </table>
    <nav aria-label="Page navigation">
      <ul class="pagination" hx-swap="outerHTML" hx-get="/data-source.php?page={{#}}">
        <!-- Pagination links will be loaded here -->
      </ul>
    </nav>
  </div>