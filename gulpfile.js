const { dest, src, parallel } = require('gulp');
const babel = require('gulp-babel');
const htmlmin = require('gulp-htmlmin');
const image = require('gulp-image');
const postcss = require('gulp-postcss');

function api() {
  return src('src/api/**/*').pipe(dest('dist/api/'));
}

function css() {
  return src('src/web/assets/scss/*.scss')
    .pipe(postcss())
    .pipe(dest('dist/web/assets/scss/'));
}

function html() {
  return src('src/web/**/*.html')
    .pipe(
      htmlmin({
        quoteCharacter: "'",
        removeAttributeQuotes: true,
        removeComments: true
      })
    )
    .pipe(dest('dist/web/'));
}

function images() {
  return src('src/web/**/*.{jpg,jpeg,png,gif,svg}')
    .pipe(image())
    .pipe(dest('dist/web'));
}

function js() {
  return src('src/web/assets/js/*.js')
    .pipe(babel())
    .pipe(dest('dist/web/assets/js'));
}

exports.build = parallel(api, images, css, html, js);
