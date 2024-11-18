<?php
  session_start();
?>

 

  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">

  <script type="text/javascript">
		if(typeof(EventSource) !== "undefined") 
		{
			const sessionChecker = new EventSource("assets/sse/session-checker.php");
			sessionChecker.onmessage = function(event) 
			{
				if(event.data === "expired")
				{
					Swal.fire({
						icon: 'warning',
						title: 'OOPS!! SESSION EXPIRED',
						}).then((result) => {
						window.location.href = "../../../set/index.php";
					});
				}

				console.log("SESSION", event.data);
			};
		} 
		else 
		{
		   console.log("Sorry! No server-sent events support..")
		}
	</script>




<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="../plugins/sweetalert2/sweetalert2.min.js"></script>


