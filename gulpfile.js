var gulp = require('gulp');
var concat = require('gulp-concat');
var sourcemaps = require('gulp-sourcemaps');
var minifyCss = require('gulp-minify-css');
var uglify = require('gulp-uglify');
var babel = require('gulp-babel');

gulp.task('style', function () {
    var source = [
        'src/Volley/FaceBundle/Resources/public/bower_components/bootstrap/dist/css/bootstrap.css',
        'src/Volley/FaceBundle/Resources/public/css/styles.css'
    ];
    gulp.src(source)
        .pipe(sourcemaps.init())
        .pipe(concat('style.css'))
        .pipe(minifyCss({
            keepSpecialComments: 0
        }))
        .pipe(sourcemaps.write("."))
        .pipe(gulp.dest('web/css/'));
});

gulp.task('style_admin', function () {
    var source = [
        'src/Volley/FaceBundle/Resources/public/css/dashboard.css'
    ];
    gulp.src(source)
        .pipe(sourcemaps.init())
        .pipe(concat('style_admin.css'))
        .pipe(minifyCss({
            keepSpecialComments: 0
        }))
        .pipe(sourcemaps.write("."))
        .pipe(gulp.dest('web/css/'));
});

gulp.task('style_ie', function () {
    var source = [
        'src/Volley/FaceBundle/Resources/public/css/ie.css'
    ];
    gulp.src(source)
        .pipe(sourcemaps.init())
        .pipe(concat('style_ie.css'))
        .pipe(minifyCss({
            keepSpecialComments: 0
        }))
        .pipe(sourcemaps.write("."))
        .pipe(gulp.dest('web/css/'));
});

gulp.task('script', function () {
    var source = [
        'src/Volley/FaceBundle/Resources/public/bower_components/jquery/dist/jquery.min.js'
    ];
    gulp.src(source)
        .pipe(sourcemaps.init())
        .pipe(babel())
        .pipe(concat('script.js'))
        .pipe(uglify())
        .pipe(sourcemaps.write("."))
        .pipe(gulp.dest('web/js/'));
});

gulp.task('script_admin', function () {
    var source = [
        'src/Volley/FaceBundle/Resources/public/bower_components/bootstrap/js/tooltip.js',
        'src/Volley/FaceBundle/Resources/public/bower_components/bootstrap/js/*.js',
        'src/Volley/FaceBundle/Resources/public/bower_components/jquery/dist/jquery.min.js',
        'src/Volley/FaceBundle/Resources/public/js/slide.js'
    ];
    gulp.src(source)
        //.pipe(sourcemaps.init())
        .pipe(babel())
        .pipe(concat('script_admin.js'))
        //.pipe(uglify())
        //.pipe(sourcemaps.write("."))
        .pipe(gulp.dest('web/js/'));
});

gulp.task('script_ie', function () {
    var source = [
        'src/Volley/FaceBundle/Resources/public/js/html5shiv.js',
        'src/Volley/FaceBundle/Resources/public/js/respond.min.js'
    ];
    gulp.src(source)
        .pipe(sourcemaps.init())
        .pipe(babel())
        .pipe(concat('script_ie.js'))
        .pipe(uglify())
        .pipe(sourcemaps.write("."))
        .pipe(gulp.dest('web/js/'));
});

gulp.task('default', ['style', 'style_admin', 'style_ie', 'script', 'script_admin', 'script_ie']);

gulp.task('watch', function () {
    gulp.start('default');
    //gulp.start(/*'sass',*/ 'scripts.app', 'scripts.vendor', 'styles'/*, 'libraries'*/);
    //gulp.watch('src/css/**/*.css', ['styles']);
    gulp.watch('src/Volley/FaceBundle/Resources/public/**/*.js', ['default']);
    //gulp.watch('src/js/**/*.js', ['scripts.app']);
});