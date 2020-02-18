import { dest,  src, task } from 'gulp';
import { phpMinify } from '@cedx/gulp-php-minify';
import replace from 'gulp-replace'

task('php', () =>
  src('src/assets/php/**/*.php')
    .pipe(phpMinify())
    .pipe(replace("require __DIR__ . '/../../../vendor/autoload.php';","require __DIR__ . '/../../vendor/autoload.php';"))
    .pipe(dest('dist/php/'))
);
