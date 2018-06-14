var gulp = require('gulp'),
    del = require('del'),
    concat = require('gulp-concat'),
    // sourcemaps = require('gulp-sourcemaps'),
    uglify = require('gulp-uglify-es').default,
    babel = require('gulp-babel');

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

gulp.task('default', gulp.parallel('js'));

gulp.task('watch', function () {
  gulp.watch(['public/js/**/*.js', '!public/js/**/*.min.js'], gulp.parallel('js'));
});
