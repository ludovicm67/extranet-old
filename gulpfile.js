var gulp = require('gulp'),
    del = require('del'),
    concat = require('gulp-concat'),
    // sourcemaps = require('gulp-sourcemaps'),
    uglify = require('gulp-uglify-es').default,
    babel = require('gulp-babel'),
    csso = require('gulp-csso');

gulp.task('js', function () {
  del([
    'public/js/app.min.js'
  ]);

  return gulp.src([
    'node_modules/jquery/dist/jquery.js',
    'node_modules/bootstrap/dist/js/bootstrap.js',
    'node_modules/select2/dist/js/select2.js',
    'public/js/**/*.js', '!public/js/**/*.min.js'
  ])
  // .pipe(sourcemaps.init())
  .pipe(concat('app.min.js'))
  .pipe(babel())
  .pipe(uglify())
  // .pipe(sourcemaps.write())
  .pipe(gulp.dest('public/js'))
});

gulp.task('css', function () {
  del([
    'public/css/style.min.css'
  ]);

  return gulp.src([
    'node_modules/bootstrap/dist/css/bootstrap.css',
    'node_modules/select2/dist/css/select2.css',
    'node_modules/select2-bootstrap4-theme/dist/select2-bootstrap4.css',
    'public/css/**/*.css', '!public/css/**/*.min.css'
  ])
  .pipe(concat('style.min.css'))
  .pipe(csso())
  .pipe(gulp.dest('public/css'))
});

gulp.task('default', gulp.parallel('js', 'css'));

gulp.task('watch', function () {
  gulp.watch(['public/js/**/*.js', '!public/js/**/*.min.js'], gulp.parallel('js'));
  gulp.watch(['public/css/**/*.css', '!public/css/**/*.min.css'], gulp.parallel('css'));
});
