let gulp = require('gulp');
let sass = require('gulp-sass');
let browserSync = require('browser-sync').create();
let uglify = require('gulp-uglify'); // for js
let uglifycss = require('gulp-uglifycss');
let autoprefixer = require('gulp-autoprefixer');

// to compile sass/scss in to css (https://www.npmjs.com/package/gulp-sass)
function style(){
    return gulp.src('scss/**/*.scss') // were are the files?
    .pipe(sass().on('error', sass.logError)) //pass the file throught sass compiler
    .pipe(autoprefixer('last 4 versions','ie >= 10')) // css autoprefix
    .pipe(gulp.dest('css/')) // were are the file saved? (zielortner)
    .pipe(browserSync.stream()); // stream changes to all browser
}

// to uglify/minify the css
function uglifyCss(){
    return gulp.src('css/*.css') // were are the files?
        .pipe(uglifycss({
            // "maxLineLen": 80, // every 80 carr new line
            "uglyComments": true
        }))
        .pipe(gulp.dest('min_css/'));
}

// to autoprefix the css
function autoprefix (){
    return gulp.src('min_css/*.css') // were are the files?
        .pipe(autoprefixer('last 4 versions','ie >= 10'))
        .pipe(gulp.dest('min_css/'));
}

// for the browser syncro
function watch(){
    browserSync.init({
        server:{
            baseDir: './'
        }
    });
    gulp.watch('scss/**/*.scss', style);
    gulp.watch('css/*.css', uglifyCss);
    //gulp.watch('./css/*.css', autoprefix);
    gulp.watch('*.html').on('change', browserSync.reload);
    gulp.watch('js/*.js').on('change', browserSync.reload);
}

exports.style = style;
//exports.uglifyCss = uglifyCss;
//exports.autoprefix = autoprefix;
exports.watch = watch;




