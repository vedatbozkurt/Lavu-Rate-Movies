<footer class="main-footer text-sm">
  <!-- To the right -->
  <div class="float-right d-none d-sm-inline">
    ... and no good thing ever dies.
  </div>
  <!-- Default to the left -->
  <strong>Copyright &copy; 2020 <a href="https://wedat.org" target="_blank">wedat.org</a>.</strong> All rights reserved.
</footer>

<aside class="control-sidebar control-sidebar-dark">
  <!-- Control sidebar content goes here -->
  <div class="p-3">
    <h5>Title</h5>
    <p>Sidebar content</p>
  </div>
</aside>
</div>
<!-- ./wrapper -->
@auth
<script>
  window.user = @json(auth()->user())
</script>
@endauth
<!-- Scripts -->
<script src="{{ asset('js/backend.js') }}" defer></script>
</body>
</html>
