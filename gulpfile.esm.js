import { dest,  src, task } from 'gulp';
import { phpMinify } from '@cedx/gulp-php-minify';

task('php', () =>
  src('src/api/**/*.php')
    .pipe(phpMinify())
    .pipe(dest('dist/api/'))
);
