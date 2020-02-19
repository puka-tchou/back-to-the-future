import { dest, src, task, parallel } from 'gulp';
import { phpMinify } from '@cedx/gulp-php-minify';

const php = () => {
  return src('src/api/**/*.php')
    .pipe(phpMinify())
    .pipe(dest('dist/api/'));
};

const copyXml = () => {
  return src('src/api/**/*.txt').pipe(dest('dist/api/'));
};

exports.build = parallel(php, copyXml);
