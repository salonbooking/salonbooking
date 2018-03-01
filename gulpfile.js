require('dotenv').config();
// grab our gulp packages
var gulp  = require('gulp'),
    gutil = require('gulp-util'),
    less = require('gulp-less'),
    watch = require( 'gulp-watch' ),
    path = require('path'),
    livereload = require( 'gulp-livereload' ),
    sass = require('gulp-sass'),
    minifyCss = require('gulp-minify-css'),
    runSequence = require('run-sequence'),
    gettext = require('gulp-potomo')
    wpPot = require("gulp-wp-pot");//
    //sourcemaps = require('gulp-sourcemaps');

var sassOptions = {
  errLogToConsole: true,
  outputStyle: 'expanded'
};

var langOptions={
  languageDir:'./languages/',
  languageDomain:'salon-booking-system',
  bugReport:process.env.POT_BUG_REPORT ? process.env.POT_BUG_REPORT : '', 
  team:process.env.POT_TEAM ? process.env.POT_TEAM : '',
};

var transifex = require('gulp-transifex').createClient({       
  user: process.env.TRANSIFEX_USER,
  password: process.env.TRANSIFEX_PASSWORD,
  project: langOptions.languageDomain,
  local_path: langOptions.languageDir    
});

//gulp.task('sass', function () {
//  gulp.src('./scss/**/*.scss')
//    //.pipe(sourcemaps.init())
//    .pipe(sass().on('error', sass.logError))
//    //.pipe(sourcemaps.write())
//    .pipe(gulp.dest('./css'))
//    .pipe( livereload() );
//});
gulp.task('sass', function () {
  gulp.src('./scss/salon.scss')
    //.pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    //.pipe(sourcemaps.write())
    .pipe(gulp.dest('./css'))
    .pipe( livereload() );
});

gulp.task('colors', function () {
  gulp.src('./scss/sln-colors--custom.scss')
    //.pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    //.pipe(sourcemaps.write())
    .pipe(gulp.dest('./css'))
    .pipe( livereload() );
});

gulp.task('adminsass', function () {
  gulp.src('./scss/admin.scss')
    //.pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    //.pipe(sourcemaps.write())
    .pipe(gulp.dest('./css'))
    .pipe( livereload() );
});
gulp.task('less', function () {
  return gulp.src('less/salon.less')
    .pipe(less({
      paths: [ path.join(__dirname, 'less', 'includes') ]
    }))
    .pipe(gulp.dest('css'))
    .pipe( livereload() );
});

gulp.task('admindtepicker', function () {
  return gulp.src('less/datepicker_admin.less')
    .pipe(less({
      paths: [ path.join(__dirname, 'less', 'includes') ]
    }))
    .pipe(gulp.dest('css'))
    .pipe( livereload() );
});

gulp.task('sbs', function () {
  return gulp.src('less/sln-bootstrap.less')
    .pipe(less({
      paths: [ path.join(__dirname, 'less', 'includes') ]
    }))
    .pipe(gulp.dest('css'));
});

gulp.task('cal', function () {
  return gulp.src('less/calendar.less')
    .pipe(less({
      paths: [ path.join(__dirname, 'less', 'includes') ]
    }))
    .pipe(gulp.dest('css'));
});

gulp.task('adm', function () {
  return gulp.src('less/admin.less')
    .pipe(less({
      paths: [ path.join(__dirname, 'less', 'includes') ]
    }))
    .pipe(gulp.dest('css'));
});

gulp.task('boot', function () {
  return gulp.src('less/bootstrap.less')
    .pipe(less({
      paths: [ path.join(__dirname, 'less', 'includes') ]
    }))
    .pipe(gulp.dest('css'));
});

gulp.task('mini', function() {
  return gulp.src('css/*.css')
    .pipe(minifyCss({compatibility: 'ie8'}))
    .pipe(gulp.dest('css'));
});

gulp.task( 'watch', function() {
  livereload.listen();
  //gulp.watch( './less/salon.less', [ 'less' ] );
  //gulp.watch( './less/admin.less', [ 'adm' ] );adminsass
  //gulp.watch( './scss/**/*.scss', [ 'sass' ] );
  //gulp.watch( './scss/**/*.scss', [ 'colors' ] );
  gulp.watch( './scss/**/*.scss', [ 'adminsass' ] );
  //gulp.watch( './css/admin.css' ).on( 'change', function( file ) {
  //  livereload.changed( file );
  //} );
 //gulp.watch( './**/*.js' ).on( 'change', function( file ) {
 //  livereload.changed( file );
 //} );
 gulp.watch( './**/*.php' ).on( 'change', function( file ) {
   livereload.changed( file );
 } );
 //gulp.watch( './**/*.html' ).on( 'change', function( file ) {
 //  livereload.changed( file );
 //} );
} );
// create a default task and just log a message
gulp.task( 'default', [ 'sass', 'adminsass', 'watch', 'boot', 'sbs' ], function() {
  livereload.listen();
        reloadPage = function (evt) {
            livereload.changed(evt.path);
        };
} );

gulp.task('generate-pot', function () {
    return gulp.src('./**/*.php')
        .pipe(wpPot( {
             domain        : langOptions.languageDomain,             
             package       : langOptions.languageDomain,
             bugReport     : langOptions.bugReport,             
             team          : langOptions.team
        }))
        .pipe(gulp.dest(langOptions.languageDir+langOptions.languageDomain+'.pot'));
});

gulp.task('transifex-up', function(){
    return gulp.src(langOptions.languageDir+'*.pot')
        .pipe(transifex.pushResource())
});

gulp.task('transifex-down', function(){
    return gulp.src(langOptions.languageDir+'*.pot')
        .pipe(transifex.pullResource())
});

gulp.task('potomo', function(){
    return gulp.src(langOptions.languageDir+'*.po')
        .pipe(gettext())
        .pipe(gulp.dest(langOptions.languageDir));
});

gulp.task('transifex', function(callback) {
  runSequence('generate-pot','transifex-up','transifex-down','potomo',
              callback);
});