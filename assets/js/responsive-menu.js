const kavaResponsiveMenu = (options = {}) => {

	const defaults = {
		wrapper: '.main-navigation',
		menu: '.menu',
		threshold: 640, // Minimal menu width,
		mobileMenuClass: 'mobile-menu',
		mobileMenuOpenClass: 'mobile-menu-open',
		mobileMenuToggleButtonClass: 'mobile-menu-toggle-button',
		toggleButtonTemplate: '<i class="mobile-menu-close fa fa-bars" aria-hidden="true"></i><i class="mobile-menu-open fa fa-times" aria-hidden="true"></i>'
	}
	options = Object.assign(defaults, options);

	const wrapper = options.wrapper.nodeType ?
		options.wrapper :
		document.querySelector(options.wrapper);

	const menu = options.menu.nodeType ?
		options.menu :
		document.querySelector(options.menu);

	let toggleButton,
		toggleButtonOpenBlock,
		toggleButtonCloseBlock,
		isMobileMenu,
		isMobileMenuOpen;

	// series

	const init = [
		addToggleButton,
		checkScreenWidth,
		addResizeHandler
	]

	if (wrapper && menu) {
		runSeries(init);
	}

	function addToggleButton() {
		toggleButton = document.createElement('button');

		toggleButton.innerHTML = options.toggleButtonTemplate.trim();
		toggleButton.className = options.mobileMenuToggleButtonClass;
		wrapper.insertBefore(toggleButton, wrapper.children[0]);

		toggleButtonOpenBlock = toggleButton.querySelector('.mobile-menu-open');
		toggleButtonCloseBlock = toggleButton.querySelector('.mobile-menu-close');

		toggleButton.addEventListener('click', mobileMenuToggle);
	}

	// menu switchers

	function switchToMobileMenu() {
		wrapper.classList.add(options.mobileMenuClass);
		toggleButton.style.display = "block";
		isMobileMenuOpen = false;
		hideMenu();
	}

	function switchToDesktopMenu() {
		wrapper.classList.remove(options.mobileMenuClass);
		toggleButton.style.display = "none";
		showMenu();
	}

	// mobile menu toggle

	function mobileMenuToggle() {
		if (isMobileMenuOpen) {
			hideMenu();
		} else {
			showMenu();
		}
		isMobileMenuOpen = !isMobileMenuOpen;
	}

	function hideMenu() {
		wrapper.classList.remove(options.mobileMenuOpenClass);
		menu.style.display = "none";
		toggleButtonOpenBlock.style.display = "none";
		toggleButtonCloseBlock.style.display = "block";
	}

	function showMenu() {
		wrapper.classList.add(options.mobileMenuOpenClass);
		menu.style.display = "block";
		toggleButtonOpenBlock.style.display = "block";
		toggleButtonCloseBlock.style.display = "none";
	}

	// resize helpers

	function checkScreenWidth() {
		let currentMobileMenuStatus = window.innerWidth < options.threshold ? true : false;

		if (isMobileMenu !== currentMobileMenuStatus) {
			isMobileMenu = currentMobileMenuStatus;
			isMobileMenu ? switchToMobileMenu() : switchToDesktopMenu();
		}
	}

	function addResizeHandler() {
		window.addEventListener('resize', resizeHandler);
	}

	function resizeHandler() {
		window.requestAnimationFrame(checkScreenWidth)
	}

	// general helpers

	function runSeries(functions) {
		functions.forEach(func => func());
	}
}