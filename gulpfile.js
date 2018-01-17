var gulp = require('gulp');
var concat = require('gulp-concat');
var sourcemaps = require('gulp-sourcemaps');
var minifyCSS = require('gulp-clean-css');
var uglify = require('gulp-uglify');
var babel = require('gulp-babel');
var clean = require('gulp-clean');
var shell = require('gulp-shell');

gulp.task('style', function () {
    var source = [
        // 'src/Volley/FaceBundle/Resources/public/owl-carousel2/components-font-awesome/css/font-awesome.min.css',
        'node_modules/font-awesome/css/font-awesome.min.css',
        // 'src/Volley/FaceBundle/Resources/public/css/boss/animate.css',
        'src/Volley/FaceBundle/Resources/public/css/boss/bootstrap.min.css',
        // 'node_modules/bootstrap/dist/css/bootstrap.css',
        // 'src/Volley/FaceBundle/Resources/public/css/animations.css',
        'src/Volley/FaceBundle/Resources/public/css/boss/style.css',
        'src/Volley/FaceBundle/Resources/public/css/boss/green.css',
        'src/Volley/FaceBundle/Resources/public/css/custom/global.css',
        'src/Volley/FaceBundle/Resources/public/css/custom/header.css',
        'src/Volley/FaceBundle/Resources/public/css/custom/body.css',
        'node_modules/owl.carousel/dist/assets/owl.carousel.min.css',
    ];
    gulp.src(source)
        // .pipe(sourcemaps.init())
        .pipe(concat('style.css'))
        .pipe(minifyCSS({level: {1: {specialComments: 0}}}))
        // .pipe(sourcemaps.write("."))
        .pipe(gulp.dest('web/css/'));
});

gulp.task('style_admin', function () {
    var source = [
        'node_modules/bootstrap/dist/css/bootstrap.css',
        'src/Volley/FaceBundle/Resources/public/css/dashboard.css',
        'node_modules/bootstrap-toggle/css/bootstrap-toggle.min.css',
        'node_modules/font-awesome/css/font-awesome.min.css',
        'src/Volley/FaceBundle/Resources/public/css/admin/styles.css'
    ];
    gulp.src(source)
        // .pipe(sourcemaps.init())
        .pipe(concat('style_admin.css'))
        .pipe(minifyCSS({level: {1: {specialComments: 0}}}))
        // .pipe(sourcemaps.write("."))
        .pipe(gulp.dest('web/css/'));
});

gulp.task('style_ie', function () {
    var source = [
        'src/Volley/FaceBundle/Resources/public/css/ie.css'
    ];
    gulp.src(source)
        // .pipe(sourcemaps.init())
        .pipe(concat('style_ie.css'))
        .pipe(minifyCSS())
        // .pipe(sourcemaps.write("."))
        .pipe(gulp.dest('web/css/'));
});

gulp.task('script_header', function () {
    var source = [
        // 'src/Volley/FaceBundle/Resources/public/js/boss/analytics.js',
        // 'src/Volley/FaceBundle/Resources/public/js/boss/modernizr.js',
        // 'src/Volley/FaceBundle/Resources/public/js/boss/jquery.min.js',
        // 'src/Volley/FaceBundle/Resources/public/js/boss/queryloader2.min.js',
    ];
    gulp.src(source)
        //.pipe(sourcemaps.init())
        // .pipe(babel())
        .pipe(concat('script_header.js'))
        .pipe(uglify())
        //.pipe(sourcemaps.write("."))
        .pipe(gulp.dest('web/js/'));
});

gulp.task('script_footer', function () {
    var source = [
        'src/Volley/FaceBundle/Resources/public/js/boss/modernizr.js',
        'src/Volley/FaceBundle/Resources/public/js/boss/jquery.min.js',
        'src/Volley/FaceBundle/Resources/public/js/boss/smoothscroll.js',
        'src/Volley/FaceBundle/Resources/public/js/boss/bootstrap.min.js',
        'src/Volley/FaceBundle/Resources/public/js/boss/jquery.hoverIntent.min.js',
        // 'src/Volley/FaceBundle/Resources/public/js/boss/jquery.nicescroll.min.js',
        'src/Volley/FaceBundle/Resources/public/js/boss/waypoints.min.js',
        'src/Volley/FaceBundle/Resources/public/js/boss/waypoints-sticky.min.js',
        // 'src/Volley/FaceBundle/Resources/public/js/boss/jquery.debouncedresize.js',
        // 'src/Volley/FaceBundle/Resources/public/js/boss/retina.min.js',
        // 'src/Volley/FaceBundle/Resources/public/js/boss/jflickrfeed.min.js',
        // 'src/Volley/FaceBundle/Resources/public/js/boss/jquery.tweet.min.js',
        // 'src/Volley/FaceBundle/Resources/public/js/boss/jquery.infinitescroll.min.js',
        // 'src/Volley/FaceBundle/Resources/public/js/boss/wow.min.js',
        // 'src/Volley/FaceBundle/Resources/public/js/boss/skrollr.min.js',
        'src/Volley/FaceBundle/Resources/public/js/boss/main.js',
        'node_modules/owl.carousel/dist/owl.carousel.min.js',
        'node_modules/jquery.cookie/jquery.cookie.js',
        'src/Volley/FaceBundle/Resources/public/js/custom/matches.js',
    ];
    gulp.src(source)
        //.pipe(sourcemaps.init())
        // .pipe(babel())
        .pipe(concat('script_footer.js'))
        .pipe(uglify())
        //.pipe(sourcemaps.write("."))
        .pipe(gulp.dest('web/js/'));
});


gulp.task('script', function () {
    gulp.start(['script_header','script_footer']);
});

gulp.task('script_admin', function () {
    var source = [
        'node_modules/bootstrap/dist/js/bootstrap.js',
        'node_modules/jquery/dist/jquery.min.js',
        'src/Volley/FaceBundle/Resources/public/js/custom/slide.js',
        'src/Volley/FaceBundle/Resources/public/js/custom/game.js',
        'node_modules/bootstrap-toggle/js/bootstrap-toggle.min.js',
    ];
    gulp.src(source)
        //.pipe(sourcemaps.init())
        .pipe(babel())
        .pipe(concat('script_admin.js'))
        .pipe(uglify())
        //.pipe(sourcemaps.write("."))
        .pipe(gulp.dest('web/js/'));
});

gulp.task('script_install', function () {
    var source = [
        'src/Volley/FaceBundle/Resources/public/js/custom/**/*.js',
        'src/Volley/StatBundle/Resources/public/js/custom/**/*.js',

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
        'src/Volley/FaceBundle/Resources/public/fonts/*',
        'node_modules/bootstrap/dist/fonts/*',
        'node_modules/font-awesome/fonts/*'
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