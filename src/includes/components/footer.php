<div class="footer">
			<div class="container">
				<div class="row">
					<div class="col-md-6 footer-left pt-xl-4 px-xl-5 d-flex justify-content-center flex-column d-flex">
						<div class="row">
							<!-- First row content -->
							<div class="col-md-12">
								<img src="images/resc/iVOTE4.png" alt="" class="iVote-logo"
									style="width: 100%; height: 3rem;">
							</div>
						</div>
						<div class="row">
							<!-- Second row content -->
							<div class="col-md-12">
								<p class="fs-10 fw-medium pt-xl-3">iVOTE is an Automated Election System (AES) for the
									student
									organizations of the PUP Santa Rosa Campus.</p>
							</div>
						</div>
						<div class="row">
							<!-- Third row content -->
							<div class="col-md-12">
								<p class="fs-12 fw-medium spacing-6 ">
									<span class="fw-bold main-red">© 2024 iVote.</span>
									<span class="fw-medium main-blue"> All Rights Reserved</span>
								</p>
							</div>
						</div>
						<div class="vertical-line"></div>
					</div>

					<div class="col-md-5 footer-right m-xl-3">
						<!-- Second column content -->
						<div class="row">
							<!-- First row content -->
							<div class="col-md-12">
								<p class="fw-bold main-red spacing-6 mb-xl-1">Connect with <?php echo strtoupper($org_acronym) ?></p>
							</div>
						</div>
						<div class="row">
							<!-- Second row content -->
							<div class="col-md-12">
								<p class="dark-gray spacing-6">
									<span class="fw-medium">Get in touch at</span>
									<span class="fw-bold">
										<a href="mailto:jpia-pup@gmail.com" class="dark-gray link"><?php echo ($org_email) ?></a>
									</span>
								</p>
							</div>
						</div>
						<div class="row justify-content-between w-35">
							<!-- Third row content -->
							<div class="col-md-12 d-flex justify-content-between pt-xl-1">
								<a href="<?php echo ($twitter) ?>">
									<img src="images/resc/icons/twitter.png" alt="" class="soc-med-icons">
								</a>
								<a href="<?php echo ($facebook) ?>">
									<img src="images/resc/icons/facebook.png" alt="" class="soc-med-icons">
								</a>
								<a href="<?php echo ($instagram) ?>">
									<img src="images/resc/icons/instagram.png" alt="" class="soc-med-icons">
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

        <script>
			function checkZoomLevel() {
				var zoomLevel = Math.round(window.devicePixelRatio * 100);

				if (zoomLevel >= 25 && zoomLevel <= 80) {
					document.querySelector('.footer').style.position = 'fixed';
					document.querySelector('.footer').style.bottom = '0';
				} else {
					document.querySelector('.footer').style.position = '';
					document.querySelector('.footer').style.bottom = '';
				}
			}

			checkZoomLevel();
			window.addEventListener('resize', checkZoomLevel);
		</script>