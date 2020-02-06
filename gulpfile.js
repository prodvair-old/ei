var gulp = require("gulp"),
  sass = require("gulp-sass"),
  browserSync = require("browser-sync"),
  concat = require("gulp-concat"),
  uglify = require("gulp-uglify-es").default,
  cleancss = require("gulp-clean-css"),
  autoprefixer = require("gulp-autoprefixer"),
  rsync = require("gulp-rsync"),
  newer = require("gulp-newer"),
  rename = require("gulp-rename");
// responsive   = require('gulp-responsive'),
// del          = require('del');

// Local Server
gulp.task("browser-sync", function() {
  browserSync({
    ui: {
      port: 3002
    },
    baseDir: "frontend/web/",
    proxy: "http://dev.ei.ru",
    port: 3003,
    ghostMode: {
      codeSync: false,
      clicks: false,
      forms: false,
      scroll: false,
      location: false
    }
    // tunnel: true, tunnel: 'ei', // Demonstration page: http://ei.localtunnel.me
  });
});

gulp.task("browser-sync-server", function() {
  browserSync({
    ui: {
      port: 3002
    },
    baseDir: "frontend/web/",
    proxy: "http://ei/",
    port: 3005,
    ghostMode: {
      codeSync: false,
      clicks: false,
      forms: false,
      scroll: false,
      location: false
    }
    // tunnel: true, tunnel: 'ei', // Demonstration page: http://ei.localtunnel.me
  });
});

gulp.task("browser-sync-max", function() {
  browserSync({
    ui: {
      port: 3002
    },
    baseDir: "frontend/web/",
    proxy: "http://ei.front:8080/",
    port: 3005,
    ghostMode: {
      codeSync: false,
      clicks: false,
      forms: false,
      scroll: false,
      location: false
    }
    // tunnel: true, tunnel: 'ei', // Demonstration page: http://ei.localtunnel.me
  });
});

function bsReload(done) {
  browserSync.reload();
  done();
}

// Custom Styles
gulp.task("styles", function() {
  return gulp
    .src("frontend/web/sass/**/*.sass")
    .pipe(
      sass({
        outputStyle: "expanded"
      })
    )
    .pipe(concat("custom.min.css"))
    .pipe(
      autoprefixer({
        grid: true,
        overrideBrowserslist: ["last 10 versions"]
      })
    )
    .pipe(
      cleancss({
        level: {
          1: {
            specialComments: 0
          }
        }
      })
    ) // Optional. Comment out when debugging
    .pipe(gulp.dest("frontend/web/css"))
    .pipe(browserSync.stream());
});

// Scripts & JS Libraries
gulp.task("scripts", function() {
  return (
    gulp
      .src([
        "frontend/web/js/_custom.js" // Custom scripts. Always at the end
      ])
      .pipe(concat("scripts.min.js"))
      // .pipe(uglify()) // Minify js (opt.)
      .pipe(gulp.dest("frontend/web/js"))
      .pipe(
        browserSync.reload({
          stream: true
        })
      )
  );
});

// Code & Reload
gulp.task("code", function() {
  return gulp.src("frontend/views/lot/index.php").pipe(
    browserSync.reload({
      stream: true
    })
  );
});

gulp.task("watch", function() {
  gulp.watch("frontend/web/sass/**/*.sass", gulp.parallel("styles"));
  gulp.watch("frontend/web/js/_custom.js", gulp.parallel("scripts"));
  gulp.watch(["frontend/views/**/*.php"], gulp.parallel("code"));
  // gulp.watch('frontend/web/img/**/*', gulp.parallel('img'));
});

gulp.task(
  "default",
  gulp.parallel("styles", "scripts", "browser-sync", "watch")
);
gulp.task(
  "max",
  gulp.parallel("styles", "scripts", "browser-sync-max", "watch")
);
gulp.task(
  "server",
  gulp.parallel("styles", "scripts", "browser-sync-server", "watch")
);
