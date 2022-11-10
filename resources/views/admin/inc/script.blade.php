<!-- plugins -->
<script src="{{asset('assets/admin/assets/js/vendors.js')}}"></script>
<script src="https://use.fontawesome.com/ba42ce05d7.js"></script>
<!-- custom app -->
<script src="{{asset('assets/admin/assets/js/app.js')}}"></script>
<script>
$('#datatable2').DataTable({
    pageLength: 10,
    filter: true,
    deferRender: true,
    scrollY: 200,
    scrollCollapse: true,
    scroller: true,
    "searching": true,
  });
  </script>