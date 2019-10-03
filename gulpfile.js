var gulp          = require('gulp'),
		gutil         = require('gulp-util' ),
    sass          = require('gulp-sass'),
    sourcemaps    = require('gulp-sourcemaps'),
    browserSync   = require('browser-sync'),
    connect       = require('gulp-connect-php'),
		concat        = require('gulp-concat'),
		uglify        = require('gulp-uglify'),
		cleancss      = require('gulp-clean-css'),
		rename        = require('gulp-rename'),
		autoprefixer  = require('gulp-autoprefixer'),
		notify        = require("gulp-notify"),
		plumber       = require("gulp-plumber"),
    rsync         = require('gulp-rsync');

var computername   = process.env.computername
function startMsg(){
  console.log("Запуск от имени компьютера " + process.env.computername );
  console.log("Локальный хостинг " + localhost);
}

// var watchStartTask = ['styles', 'js'];
var watchStartTask = ['styles', 'js', 'browser-sync'];
var watchStartTask_cp = ['browser-sync-cp'];

gulp.task('browser-sync', function() {
    connect.server({

    }, function (){
      connect.server({}, function (){
        browserSync({
          ui: {
            port: 3002
          },
          baseDir: 'frontend/web/',
          proxy: localhost,
          port: 3004,
          ghostMode: {
            codeSync: false,
            clicks: false,
            forms: false,
            scroll: false,
            location: false                                                                                                                                                                                           
        }
        });
      });
    });
});
gulp.task('browser-sync-cp', function() {
  connect.server({

  }, function (){
    connect.server({}, function (){
      browserSync({
        ui: {
          port: 3003
        },
        baseDir: 'backend/web/',
        proxy: 'http://localhost.cp',
        port: 3005,
        ghostMode: {
          clicks: false,
          forms: false,
          scroll: false,
          location: false
      }
      });
    });
  });
});

gulp.task('styles', function() {
  return gulp.src('frontend/web/sass/**/*.sass')
  .pipe(sourcemaps.init())
	.pipe(sass({ outputStyle: 'expanded' }).on("error", notify.onError()))
	.pipe(rename({ suffix: '.min', prefix : '' }))
	.pipe(autoprefixer(['last 15 versions']))
  .pipe(cleancss( {level: { 1: { specialComments: 0 } } })) // Opt., comment out when debugging
  .pipe(sourcemaps.write())
	.pipe(gulp.dest('frontend/web/css'))
	.pipe(browserSync.stream());
});

gulp.task('js', function() {
	return gulp.src([
    'frontend/web/js/common.js',
		])
	.pipe(concat('scripts.min.js'))
	// .pipe(uglify()) // Mifify js (opt.)
	.pipe(gulp.dest('frontend/web/js'))
	.pipe(browserSync.reload({ stream: true }));
});

gulp.task('frontend', watchStartTask , function() {
	gulp.watch(['frontend/web/sass/**/*.sass', 'frontend/web/libs/bootstrap/**/*.scss'], ['styles']);
  gulp.watch(['frontend/web/libs/**/*.js', 'frontend/web/js/common.js','frontend/web/js/**/*.js'], ['js']);
  gulp.watch('frontend/web/**/*.svg' , browserSync.reload);
  gulp.watch(['frontend/views/**/*.php' ] , browserSync.reload);
});
gulp.task('backend', watchStartTask_cp , function() {
	gulp.watch(['backend/web/sass/**/*.sass', 'backend/web/libs/bootstrap/**/*.scss'], ['styles']);
  gulp.watch(['backend/web/libs/**/*.js', 'backend/web/css/main.js'], ['js']);
  gulp.watch('backend/web/**/*.svg' , browserSync.reload);
  gulp.watch(['backend/controllers/**/*.php', 'backend/views/**/*.php' ] , browserSync.reload);
});

gulp.task('default', ['frontend']);
gulp.task('admin', ['backend']);
