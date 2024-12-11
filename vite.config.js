import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/js/app.js',   // Main JavaScript file
        'resources/css/app.css',  // Main CSS file
	'resources/assets/vendor/scss/pages/page-auth.scss',	
	'resources/assets/vendor/fonts/boxicons.scss',
	'resources/assets/vendor/scss/core.scss',
	'resources/assets/vendor/scss/theme-default.scss',
	'resources/assets/css/demo.css',
	'resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.scss',
	'resources/assets/vendor/js/helpers.js',
	'resources/assets/js/config.js',
	'resources/assets/vendor/libs/jquery/jquery.js',
	'resources/assets/vendor/libs/popper/popper.js',
	'resources/assets/vendor/js/bootstrap.js',
	'resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js',
	'resources/assets/vendor/js/menu.js',
	'resources/assets/js/main.js',
	'resources/assets/vendor/libs/apex-charts/apex-charts.scss',
	'resources/assets/vendor/libs/apex-charts/apexcharts.js',
	'resources/assets/js/dashboards-analytics.js',
	'resources/assets/vendor/libs/custom-date/custom-date.js',
	'resources/assets/js/pages-account-settings-account.js'	
	
      ],
      refresh: true,
    }),
  ],
});
