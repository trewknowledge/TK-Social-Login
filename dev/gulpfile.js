const gulp = require('gulp');
const named = require('vinyl-named');
const	$ = require('gulp-load-plugins')();
const webpack = require('webpack-stream');

const paths = {
	src: {
		php: '../**/*.php',
		js: {
			admin: './assets/js/admin.js',
			public: './assets/js/public.js'
		},
		css: {
			admin: './assets/css/admin.scss',
			public: './assets/css/public.scss'
		}
	},
	dest: {
		css: '../assets/css/',
		js: '../assets/js/',
		pot: '../languages/'
	}
};

function errorLog(error) {
    console.log(error.message);
    this.emit('end');
}

gulp.task('pot', function() {
	return gulp.src( paths.src.php )
		.pipe( $.wpPot( {
			domain: 'vip-social-login'
		} ) )
		.pipe( gulp.dest( paths.dest.pot + 'vip-social-login.pot' ) );
});

gulp.task('css', function() {
	return gulp.src( [paths.src.css.admin, paths.src.css.public] )
		.pipe( $.sass( {
			outputStyle: 'compressed'
		} ) )
		.on('error', errorLog)
		.pipe( $.autoprefixer( 'last 4 versions' ) )
		.pipe( gulp.dest( paths.dest.css ) )
		.pipe( $.livereload() )
		.pipe( $.notify( {
			message: 'SASS style task complete'
		} ) );
});

gulp.task('js', function() {
	return gulp.src( [paths.src.js.admin, paths.src.js.public] )
		.pipe(named())
		.pipe(webpack( require('./webpack.config.js') ))
		.on('error', errorLog)
		.pipe( gulp.dest( paths.dest.js ) )
		.pipe($.livereload())
		.pipe( $.notify( {
			message: 'JS script task complete'
		} ) );
});

gulp.task('watch', function(){
	$.livereload.listen();
	gulp.watch( paths.src.php, ['pot', $.livereload.reload] );
	gulp.watch( [paths.src.css.admin, paths.src.css.public], ['css'] );
	gulp.watch( [paths.src.js.admin, paths.src.js.public], ['js'] );
});

gulp.task('default', ['pot', 'css', 'js', 'watch']);
