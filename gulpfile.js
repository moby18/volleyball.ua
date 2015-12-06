var gulp = require('gulp');
var concat = require('gulp-concat');
var sourcemaps = require('gulp-sourcemaps');
var minifyCss = require('gulp-minify-css');
var uglify = require('gulp-uglify');
var babel = require('gulp-babel');
var clean = require('gulp-clean');
var shell = require('gulp-shell');

gulp.task('style', function () {
    var source = [
        'src/Volley/FaceBundle/Resources/public/bower_components/bootstrap/dist/css/bootstrap.css',
        'src/Volley/FaceBundle/Resources/public/css/animations.css',
        'src/Volley/FaceBundle/Resources/public/css/styles.css'
    ];
    gulp.src(source)
        //.pipe(sourcemaps.init())
        .pipe(concat('style.css'))
        //.pipe(minifyCss({
        //    keepSpecialComments: 0
        //}))
        //.pipe(sourcemaps.write("."))
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
        'src/Volley/FaceBundle/Resources/public/bower_components/jquery/dist/jquery.min.js',
        'src/Volley/FaceBundle/Resources/public/bower_components/bootstrap/dist/js/bootstrap.js',
        //'src/Volley/FaceBundle/Resources/public/bower_components/bootstrap/js/dropdown.js',
        'src/Volley/FaceBundle/Resources/public/js/custom/menu.js'
    ];
    gulp.src(source)
        //.pipe(sourcemaps.init())
        .pipe(babel())
        .pipe(concat('script.js'))
        //.pipe(uglify())
        //.pipe(sourcemaps.write("."))
        .pipe(gulp.dest('web/js/'));
});

gulp.task('script_admin', function () {
    var source = [
        //'src/Volley/FaceBundle/Resources/public/bower_components/bootstrap/js/tooltip.js',
        //'src/Volley/FaceBundle/Resources/public/bower_components/bootstrap/js/*.js',
        'src/Volley/FaceBundle/Resources/public/bower_components/bootstrap/dist/js/bootstrap.js',
        'src/Volley/FaceBundle/Resources/public/bower_components/jquery/dist/jquery.min.js',
        'src/Volley/FaceBundle/Resources/public/js/custom/jquery.datetimepicker.js',
        'src/Volley/FaceBundle/Resources/public/js/custom/slide.js',
        'src/Volley/FaceBundle/Resources/public/js/custom/post.js'
    ];
    gulp.src(source)
        //.pipe(sourcemaps.init())
        .pipe(babel())
        .pipe(concat('script_admin.js'))
        //.pipe(uglify())
        //.pipe(sourcemaps.write("."))
        .pipe(gulp.dest('web/js/'));
});

gulp.task('script_install', function () {
    var source = [
        'src/Volley/FaceBundle/Resources/public/js/custom/**/*.js'
    ];
    gulp.src(source)
        //.pipe(sourcemaps.init())
        //.pipe(babel())
        //.pipe(uglify())
        //.pipe(sourcemaps.write("."))
        .pipe(gulp.dest('web/js/custom/'));
});

gulp.task('script_ie', function () {
    var source = [
        'src/Volley/FaceBundle/Resources/public/js/html5shiv.js',
        'src/Volley/FaceBundle/Resources/public/js/respond.min.js'
    ];
    gulp.src(source)
        //.pipe(sourcemaps.init())
        .pipe(babel())
        .pipe(concat('script_ie.js'))
        .pipe(uglify())
        //.pipe(sourcemaps.write("."))
        .pipe(gulp.dest('web/js/'));
});

gulp.task('fonts', function () {
    return gulp.src([
        'src/Volley/FaceBundle/Resources/public/bower_components/bootstrap/dist/fonts/*'
    ])
        .pipe(gulp.dest('web/fonts/'))
});

gulp.task('assets_install', shell.task([
    'app/console assets:install'
]));

gulp.task('clean', function () {
    return gulp.src(['web/css/*', 'web/js/*', 'web/images/*', 'web/fonts/*'/*, 'web/bundles/*'*/])
        .pipe(clean());
});

gulp.task('default', ['clean'], function() {
    gulp.start(['fonts', 'style', 'style_admin', 'style_ie', 'script', 'script_admin', 'script_ie', 'script_install']);
});

gulp.task('front', [], function() {
    gulp.start(['fonts', 'style', 'script', 'script_install']);
});

gulp.task('watch', ['clean'], function () {
    gulp.start('default'/*,'assets_install'*/);

    gulp.watch('src/Volley/FaceBundle/Resources/public/**/*.css', ['style', 'style_admin', 'style_ie']);
    gulp.watch('src/Volley/FaceBundle/Resources/public/**/*.js', ['script', 'script_admin', 'script_ie', 'script_install']);
    //gulp.watch('src/Volley/FaceBundle/Resources/public/**/*', ['assets_install']);
});