var
	gulp 		= require("gulp"),
	browserSync = require('browser-sync').create();

gulp.task('watch', function () {
  gulp.watch(["./**/*.php","*.html","js/app.js"]).on('change', gulp.parallel(browserSync.reload));
});
gulp.task('ws', function() {
    browserSync.init({
        proxy: "localhost:8000",
        notify: false,
	    snippetOptions: {
	        rule: {
	            match: /$/
	        }
	    }
    });
});
gulp.task('init', gulp.series('ws'));


gulp.task("default",gulp.parallel('watch','init'));