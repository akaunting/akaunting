/**
 * Auto-reun tests in development
 */

// Setup
var gulp = require('gulp')
	, phpunit = require('gulp-phpunit')
;

// PHPunit
gulp.task('phpunit', function() {
	var options = {
		debug: false
		, clear: true
	};
	gulp
		.src('phpunit.xml')
		.pipe(phpunit('./vendor/phpunit/phpunit/phpunit', options))
		.on('error', function(){})
	;
});

// Watch files
gulp.task('watch', function () {
	gulp.watch('**/*.php', ['phpunit']);
});

// Default task
gulp.task('default', ['watch']);