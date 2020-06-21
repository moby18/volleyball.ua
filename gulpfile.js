const {src, dest, parallel, series, watch} = require('gulp')
const concat = require('gulp-concat')
const sourcemaps = require('gulp-sourcemaps')
const minifyCSS = require('gulp-clean-css')
const uglify = require('gulp-uglify')
const uglify_es = require('gulp-uglify-es').default
const babel = require('gulp-babel')
const cleanFolder = require('gulp-clean')
const sass = require('gulp-sass')
sass.compiler = require('node-sass')
const exec = require('child_process').exec

function style () {
  const source = [
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
    // 'src/Volley/FaceBundle/Resources/public/css/custom/body.css',
    'src/Volley/FaceBundle/Resources/public/scss/custom/body.scss',
    'node_modules/owl.carousel/dist/assets/owl.carousel.min.css',
  ]
  return src(source)
  // .pipe(sourcemaps.init())
  .pipe(concat('style.scss'))
  .pipe(sass().on('error', sass.logError))
  .pipe(minifyCSS({level: {1: {specialComments: 0}}}))
  // .pipe(sourcemaps.write("."))
  .pipe(dest('web/css/'))
}

function style_admin () {
  const source = [
    'node_modules/bootstrap/dist/css/bootstrap.css',
    'src/Volley/FaceBundle/Resources/public/css/dashboard.css',
    'node_modules/bootstrap-toggle/css/bootstrap-toggle.min.css',
    'node_modules/font-awesome/css/font-awesome.min.css',
    'src/Volley/FaceBundle/Resources/public/css/admin/styles.css'
  ]
  return src(source)
  // .pipe(sourcemaps.init())
  .pipe(concat('style_admin.css'))
  .pipe(minifyCSS({level: {1: {specialComments: 0}}}))
  // .pipe(sourcemaps.write("."))
  .pipe(dest('web/css/'))
}

function style_ie () {
  const source = [
    'src/Volley/FaceBundle/Resources/public/css/ie.css'
  ]
  return src(source)
  // .pipe(sourcemaps.init())
  .pipe(concat('style_ie.css'))
  .pipe(minifyCSS())
  // .pipe(sourcemaps.write("."))
  .pipe(dest('web/css/'))
}

function script_header (cb) {
  const source = [
    // 'src/Volley/FaceBundle/Resources/public/js/boss/analytics.js',
    // 'src/Volley/FaceBundle/Resources/public/js/boss/modernizr.js',
    // 'src/Volley/FaceBundle/Resources/public/js/boss/jquery.min.js',
    // 'src/Volley/FaceBundle/Resources/public/js/boss/queryloader2.min.js',
  ]
  if (source.length) {
    return src(source)
    //.pipe(sourcemaps.init())
    // .pipe(babel())
    .pipe(concat('script_header.js'))
    .pipe(uglify())
    //.pipe(sourcemaps.write("."))
    .pipe(dest('web/js/'))
  }
  cb()
}

function script_footer () {
  const source = [
    'src/Volley/FaceBundle/Resources/public/js/boss/modernizr.js',
    'src/Volley/FaceBundle/Resources/public/js/boss/jquery.min.js',
    // 'src/Volley/FaceBundle/Resources/public/js/boss/smoothscroll.js',
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
    // 'node_modules/jquery.cookie/jquery.cookie.js',
    'src/Volley/FaceBundle/Resources/public/js/custom/game-center.js',
    // 'node_modules/owl.carousel/dist/owl.carousel.min.js',
    // 'src/Volley/FaceBundle/Resources/public/js/custom/matches.js',
  ]
  return src(source)
  //.pipe(sourcemaps.init())
  // .pipe(babel())
  .pipe(concat('script_footer.js'))
  .pipe(uglify())
  //.pipe(sourcemaps.write("."))
  .pipe(dest('web/js/'))
}

function script_admin () {
  const source = [
    'node_modules/bootstrap/dist/js/bootstrap.min.js',
    'node_modules/jquery/dist/jquery.min.js',
    'node_modules/bootstrap-toggle/js/bootstrap-toggle.min.js',
    'src/Volley/FaceBundle/Resources/public/js/custom/slide.js',
  ]
  return src(source)
  //.pipe(sourcemaps.init())
  .pipe(babel())
  .pipe(concat('script_admin.js'))
  .pipe(uglify_es())
  //.pipe(sourcemaps.write("."))
  .pipe(dest('web/js/'))
}

function script_install () {
  const source = [
    'src/Volley/FaceBundle/Resources/public/js/custom/**/*.js',
    'src/Volley/StatBundle/Resources/public/js/custom/**/*.js',

  ]
  return src(source)
  //.pipe(sourcemaps.init())
  //.pipe(babel())
  //.pipe(uglify())
  //.pipe(sourcemaps.write("."))
  .pipe(dest('web/js/custom/'))
}

function script_ie () {
  const source = [
    'src/Volley/FaceBundle/Resources/public/js/html5shiv.js',
    'src/Volley/FaceBundle/Resources/public/js/respond.min.js'
  ]
  return src(source)
  //.pipe(sourcemaps.init())
  .pipe(babel())
  .pipe(concat('script_ie.js'))
  .pipe(uglify())
  //.pipe(sourcemaps.write("."))
  .pipe(dest('web/js/'))
}

function fonts () {
  return src([
    'src/Volley/FaceBundle/Resources/public/fonts/*',
    'node_modules/bootstrap/dist/fonts/*',
    'node_modules/font-awesome/fonts/*'
  ])
  .pipe(dest('web/fonts/'))
}

function assets_install (cb) {
  exec('bin/console assets:install web')
  cb()
}

function clean () {
  return src(['web/css/*', 'web/js/*', 'web/images/*', 'web/fonts/*'/*, 'web/bundles/*'*/])
  .pipe(cleanFolder())
}

exports.style = style
exports.style_admin = style_admin
exports.style_ie = style_ie
exports.script_header = script_header
exports.script_footer = script_footer
exports.script = series(script_header, script_footer)
exports.script_admin = script_admin
exports.script_install = script_install
exports.script_ie = script_ie
exports.fonts = fonts
exports.assets_install = assets_install
exports.front = parallel(fonts, style, exports.script, script_install)
exports.clean = clean
exports.default = series(
  clean,
  parallel(
    fonts,
    style,
    style_admin,
    style_ie,
    exports.script,
    script_admin,
    script_ie,
    script_install
  )
)
exports.watch = function () {
  watch(
    [
      'src/Volley/FaceBundle/Resources/public/**/*.css',
      'src/Volley/FaceBundle/Resources/public/**/*.scss'
    ],
    parallel(style, style_admin, style_ie)
  )
  watch(
    'src/Volley/FaceBundle/Resources/public/**/*.js',
    parallel(exports.script, script_admin, script_ie, script_install)
  )
}
