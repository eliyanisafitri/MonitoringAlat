<!doctype html>
<html lang="en">

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Kaiadmin - Bootstrap 5 Admin Dashboard</title>
	<meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
		name="viewport" />
	<link rel="icon"
		href="{{ asset('template/assets/img/kaiadmin/favicon.ico') }}"
		type="image/x-icon" />
	<link rel="stylesheet"
		href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

	<link rel="stylesheet" href="{{ asset('template/assets/css/custom.css') }}">

	<!-- Fonts and icons -->
	<script src="{{ asset('template/assets/js/plugin/webfont/webfont.min.js') }}">
	</script>
	<script>
		WebFont.load({
			google: {
				families: ["Public Sans:300,400,500,600,700"]
			},
			custom: {
				families: [
					"Font Awesome 5 Solid",
					"Font Awesome 5 Regular",
					"Font Awesome 5 Brands",
					"simple-line-icons",
				],
				urls: ["assets/css/fonts.min.css"],
			},
			active: function() {
				sessionStorage.fonts = true;
			},
		});
	</script>

	<!-- CSS Files -->
	<link rel="stylesheet"
		href="{{ asset('template/assets/css/bootstrap.min.css') }}" />
	<link rel="stylesheet"
		href="{{ asset('template/assets/css/plugins.min.css') }}" />
	<link rel="stylesheet"
		href="{{ asset('template/assets/css/kaiadmin.min.css') }}" />

	<!-- CSS Just for demo purpose, don't include it in your project -->
	<link rel="stylesheet" href="{{ asset('template/assets/css/demo.css') }}" />
</head>

<body>
	<div class="wrapper">
		<!-- Sidebar -->
		<div class="sidebar" data-background-color="dark">
			<div class="sidebar-logo">
				<!-- Logo Header -->
				<div class="logo-header" data-background-color="dark">
					<a href="index.html" class="logo">
						<img src="{{ asset('template/assets/img/kaiadmin/logo_light.svg') }}"
							alt="navbar brand" class="navbar-brand" height="20" />
					</a>
					<div class="nav-toggle">
						<button class="btn btn-toggle toggle-sidebar">
							<i class="gg-menu-right"></i>
						</button>
						<button class="btn btn-toggle sidenav-toggler">
							<i class="gg-menu-left"></i>
						</button>
					</div>
					<button class="topbar-toggler more">
						<i class="gg-more-vertical-alt"></i>
					</button>
				</div>
				<!-- End Logo Header -->
			</div>
			<div class="sidebar-wrapper scrollbar scrollbar-inner">
				<div class="sidebar-content">
					<ul class="nav nav-secondary">

						<!-- Dashboard -->
						<li class="nav-item">
							<a href="{{ route('dashboard') }}">
								<i class="fas fa-home"></i>
								<p>Dashboard</p>
							</a>
						</li>

						<!-- Header Menu -->
						<li class="nav-section">
							<span class="sidebar-mini-icon">
								<i class="fa fa-ellipsis-h"></i>
							</span>
							<h4 class="text-section">PENGELOLAAN GUDANG</h4>
						</li>

						<!-- Data Master (Dropdown) -->
						<li class="nav-item">
							<a data-bs-toggle="collapse" href="#masterData" class="collapsed"
								aria-expanded="false">
								<i class="fas fa-database"></i>
								<p>Data Master</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="masterData">
								<ul class="nav nav-collapse">
									<li>
										<a href="{{ route('teknisi.index') }}">
											<span class="sub-item">Data Teknisi</span>
										</a>
									</li>
									<li>
										<a href="{{ route('alat.index') }}">
											<span class="sub-item">Data Alat</span>
										</a>
									</li>
								</ul>
							</div>
						</li>

						<!-- Transaksi Peminjaman -->
						<li class="nav-item">
							<a href="{{ route('peminjaman.index') }}">
								<i class="fas fa-dolly-flatbed"></i>
								<p>Transaksi Peminjaman </p>
							</a>
						</li>

					</ul>
				</div>
			</div>
		</div>
		<!-- End Sidebar -->

		<div class="main-panel">
			<div class="main-header">
				<div class="main-header-logo">
					<!-- Logo Header -->
					<div class="logo-header" data-background-color="dark">
						<a href="index.html" class="logo">
							<img src="{{ asset('template/assets/img/kaiadmin/logo_light.svg') }}"
								alt="navbar brand" class="navbar-brand" height="20" />
						</a>
						<div class="nav-toggle">
							<button class="btn btn-toggle toggle-sidebar">
								<i class="gg-menu-right"></i>
							</button>
							<button class="btn btn-toggle sidenav-toggler">
								<i class="gg-menu-left"></i>
							</button>
						</div>
						<button class="topbar-toggler more">
							<i class="gg-more-vertical-alt"></i>
						</button>
					</div>
					<!-- End Logo Header -->
				</div>
				<!-- Navbar Header -->
				<nav
					class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
					<div class="container-fluid">
						<nav
							class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search d-none d-lg-flex p-0">
							<div class="input-group">
								<div class="input-group-prepend">
									<button type="submit" class="btn btn-search pe-1">
										<i class="fa fa-search search-icon"></i>
									</button>
								</div>
								<input type="text" placeholder="Search ..." class="form-control" />
							</div>
						</nav>

						<ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
							<li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
								<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
									href="#" role="button" aria-expanded="false"
									aria-haspopup="true">
									<i class="fa fa-search"></i>
								</a>
								<ul class="dropdown-menu dropdown-search animated fadeIn">
									<form class="navbar-left navbar-form nav-search">
										<div class="input-group">
											<input type="text" placeholder="Search ..."
												class="form-control" />
										</div>
									</form>
								</ul>
							</li>

							<li class="nav-item topbar-user dropdown hidden-caret">
								<a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown"
									href="#" aria-expanded="false">
									<div class="avatar-sm">
										<img src="{{ asset('template/assets/img/profile.jpg') }}"
											alt="..." class="avatar-img rounded-circle" />
									</div>
									<span class="profile-username">
										<span class="op-7">Hi,</span>
										<span class="fw-bold">{{ session('nama') }}</span>
									</span>
								</a>

								<ul class="dropdown-menu dropdown-user animated fadeIn">
									<li>
										<form action="{{ route('logout') }}" method="POST" class="m-0"
											onsubmit="return confirm('Yakin ingin keluar?')">
											@csrf
											<button type="submit" class="dropdown-item text-danger fw-bold">
												<i class="fas fa-sign-out-alt me-2"></i> Keluar
											</button>
										</form>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</nav>
				<!-- End Navbar -->
			</div>

			<div class="container">
				{{-- //dashboard --}}
				{{-- <div class="page-inner">
					<div
						class="d-flex align-items-left align-items-md-center flex-column flex-md-row pb-4 pt-2">
						<div>
							<h3 class="fw-bold mb-3">Dashboard</h3>
							<h6 class="op-7 mb-2">
								Free Bootstrap 5 Admin Dashboard
							</h6>
						</div>
						<div class="ms-md-auto py-md-0 py-2">
							<a href="#" class="btn btn-label-info btn-round me-2">Manage</a>
							<a href="#" class="btn btn-primary btn-round">Add Customer</a>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-round">
								<div class="card-body">
									<div class="row align-items-center">
										<div class="col-icon">
											<div class="icon-big icon-primary bubble-shadow-small text-center">
												<i class="fas fa-users"></i>
											</div>
										</div>
										<div class="col col-stats ms-sm-0 ms-3">
											<div class="numbers">
												<p class="card-category">
													Visitors
												</p>
												<h4 class="card-title">
													1,294
												</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-round">
								<div class="card-body">
									<div class="row align-items-center">
										<div class="col-icon">
											<div class="icon-big icon-info bubble-shadow-small text-center">
												<i class="fas fa-user-check"></i>
											</div>
										</div>
										<div class="col col-stats ms-sm-0 ms-3">
											<div class="numbers">
												<p class="card-category">
													Subscribers
												</p>
												<h4 class="card-title">
													1303
												</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-round">
								<div class="card-body">
									<div class="row align-items-center">
										<div class="col-icon">
											<div class="icon-big icon-success bubble-shadow-small text-center">
												<i class="fas fa-luggage-cart"></i>
											</div>
										</div>
										<div class="col col-stats ms-sm-0 ms-3">
											<div class="numbers">
												<p class="card-category">
													Sales
												</p>
												<h4 class="card-title">
													$ 1,345
												</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-round">
								<div class="card-body">
									<div class="row align-items-center">
										<div class="col-icon">
											<div
												class="icon-big icon-secondary bubble-shadow-small text-center">
												<i class="far fa-check-circle"></i>
											</div>
										</div>
										<div class="col col-stats ms-sm-0 ms-3">
											<div class="numbers">
												<p class="card-category">
													Order
												</p>
												<h4 class="card-title">
													576
												</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> --}}
				@yield('content')
			</div>

			<footer class="footer">
				<div class="container-fluid d-flex justify-content-between">
					<nav class="pull-left">
						<ul class="nav">
							<li class="nav-item">
								<a class="nav-link" href="http://www.themekita.com">
									ThemeKita
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#"> Help </a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#"> Licenses </a>
							</li>
						</ul>
					</nav>
					<div class="copyright">
						2026, made with
						<i class="fa fa-heart heart text-danger"></i> by
						<a href="http://www.themekita.com">es.eskom</a>
					</div>
					<div>
						Distributed by
						<a target="_blank" href="https://themewagon.com/">ThemeWagon</a>.
					</div>
				</div>
			</footer>
		</div>

		<!-- Custom template | don't include it in your project! -->
		<div class="custom-template">
			<div class="title">Settings</div>
			<div class="custom-content">
				<div class="switcher">
					<div class="switch-block">
						<h4>Logo Header</h4>
						<div class="btnSwitch">
							<button type="button" class="selected changeLogoHeaderColor"
								data-color="dark"></button>
							<button type="button" class="changeLogoHeaderColor"
								data-color="blue"></button>
							<button type="button" class="changeLogoHeaderColor"
								data-color="purple"></button>
							<button type="button" class="changeLogoHeaderColor"
								data-color="light-blue"></button>
							<button type="button" class="changeLogoHeaderColor"
								data-color="green"></button>
							<button type="button" class="changeLogoHeaderColor"
								data-color="orange"></button>
							<button type="button" class="changeLogoHeaderColor"
								data-color="red"></button>
							<button type="button" class="changeLogoHeaderColor"
								data-color="white"></button>
							<br />
							<button type="button" class="changeLogoHeaderColor"
								data-color="dark2"></button>
							<button type="button" class="changeLogoHeaderColor"
								data-color="blue2"></button>
							<button type="button" class="changeLogoHeaderColor"
								data-color="purple2"></button>
							<button type="button" class="changeLogoHeaderColor"
								data-color="light-blue2"></button>
							<button type="button" class="changeLogoHeaderColor"
								data-color="green2"></button>
							<button type="button" class="changeLogoHeaderColor"
								data-color="orange2"></button>
							<button type="button" class="changeLogoHeaderColor"
								data-color="red2"></button>
						</div>
					</div>
					<div class="switch-block">
						<h4>Navbar Header</h4>
						<div class="btnSwitch">
							<button type="button" class="changeTopBarColor"
								data-color="dark"></button>
							<button type="button" class="changeTopBarColor"
								data-color="blue"></button>
							<button type="button" class="changeTopBarColor"
								data-color="purple"></button>
							<button type="button" class="changeTopBarColor"
								data-color="light-blue"></button>
							<button type="button" class="changeTopBarColor"
								data-color="green"></button>
							<button type="button" class="changeTopBarColor"
								data-color="orange"></button>
							<button type="button" class="changeTopBarColor"
								data-color="red"></button>
							<button type="button" class="selected changeTopBarColor"
								data-color="white"></button>
							<br />
							<button type="button" class="changeTopBarColor"
								data-color="dark2"></button>
							<button type="button" class="changeTopBarColor"
								data-color="blue2"></button>
							<button type="button" class="changeTopBarColor"
								data-color="purple2"></button>
							<button type="button" class="changeTopBarColor"
								data-color="light-blue2"></button>
							<button type="button" class="changeTopBarColor"
								data-color="green2"></button>
							<button type="button" class="changeTopBarColor"
								data-color="orange2"></button>
							<button type="button" class="changeTopBarColor"
								data-color="red2"></button>
						</div>
					</div>
					<div class="switch-block">
						<h4>Sidebar</h4>
						<div class="btnSwitch">
							<button type="button" class="changeSideBarColor"
								data-color="white"></button>
							<button type="button" class="selected changeSideBarColor"
								data-color="dark"></button>
							<button type="button" class="changeSideBarColor"
								data-color="dark2"></button>
						</div>
					</div>
				</div>
			</div>
			<div class="custom-toggle">
				<i class="icon-settings"></i>
			</div>
		</div>
		<!-- End Custom template -->
	</div>
	<!--   Core JS Files   -->
	<script src="{{ asset('template/assets/js/core/jquery-3.7.1.min.js') }}">
	</script>
	<script src="{{ asset('template/assets/js/core/popper.min.js') }}"></script>
	<script src="{{ asset('template/assets/js/core/bootstrap.min.js') }}"></script>

	<!-- jQuery Scrollbar -->
	<script
		src="{{ asset('template/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}">
	</script>

	<!-- Chart JS -->
	<script src="{{ asset('template/assets/js/plugin/chart.js/chart.min.js') }}">
	</script>

	<!-- jQuery Sparkline -->
	<script
		src="{{ asset('template/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}">
	</script>

	<!-- Chart Circle -->
	<script
		src="{{ asset('template/assets/js/plugin/chart-circle/circles.min.js') }}">
	</script>

	<!-- Datatables -->
	<script
		src="{{ asset('template/assets/js/plugin/datatables/datatables.min.js') }}">
	</script>

	<!-- Bootstrap Notify -->
	<script
		src="{{ asset('template/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}">
	</script>

	<!-- jQuery Vector Maps -->
	<script
		src="{{ asset('template/assets/js/plugin/jsvectormap/jsvectormap.min.js') }}">
	</script>
	<script src="{{ asset('template/assets/js/plugin/jsvectormap/world.js') }}">
	</script>

	<!-- Sweet Alert -->
	<script
		src="{{ asset('template/assets/js/plugin/sweetalert/sweetalert.min.js') }}">
	</script>

	<!-- Kaiadmin JS -->
	<script src="{{ asset('template/assets/js/kaiadmin.min.js') }}"></script>

	<!-- Kaiadmin DEMO methods, don't include it in your project! -->
	<script src="{{ asset('template/assets/js/setting-demo.js') }}"></script>
	<script src="{{ asset('template/assets/js/demo.js') }}"></script>
	<script>
		$("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
			type: "line",
			height: "70",
			width: "100%",
			lineWidth: "2",
			lineColor: "#177dff",
			fillColor: "rgba(23, 125, 255, 0.14)",
		});

		$("#lineChart2").sparkline([99, 125, 122, 105, 110, 124, 115], {
			type: "line",
			height: "70",
			width: "100%",
			lineWidth: "2",
			lineColor: "#f3545d",
			fillColor: "rgba(243, 84, 93, .14)",
		});

		$("#lineChart3").sparkline([105, 103, 123, 100, 95, 105, 115], {
			type: "line",
			height: "70",
			width: "100%",
			lineWidth: "2",
			lineColor: "#ffa534",
			fillColor: "rgba(255, 165, 52, .14)",
		});
	</script>
	<!-- Kaiadmin JS -->
	<script src="{{ asset('template/assets/js/kaiadmin.min.js') }}"></script>

	<!-- Kaiadmin DEMO methods, don't include it in your project! -->
	<script src="{{ asset('template/assets/js/setting-demo.js') }}"></script>
	<script src="{{ asset('template/assets/js/demo.js') }}"></script>

	<script>
		function konfirmasiLogout() {
			if (confirm(
					'Apakah Anda yakin ingin keluar dari sistem Gudang Pak Anto?')) {
				document.getElementById('formLogout').submit();
			}
		}
	</script>
	@stack('scripts')

</body>

</html>
