const { dest, src, parallel } = require('gulp');
const babel = require('gulp-babel');
const htmlmin = require('gulp-htmlmin');
const postcss = require('gulp-postcss');

function api() {
  return src('src/api/**/*').pipe(dest('dist/api/'));
}

function assets() {
  return src('src/web/assets/images/**/*').pipe(dest('dist/web/assets/images'));
}

function css() {
  return src('src/web/assets/css/*.css')
    .pipe(postcss())
    .pipe(dest('dist/web/assets/css/'));
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

function js() {
  return src('src/web/assets/js/*.js')
    .pipe(babel())
    .pipe(dest('dist/web/assets/js'));
}

exports.build = parallel(api, assets, css, html, js);
