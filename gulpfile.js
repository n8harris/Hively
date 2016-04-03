var config = require('./gulp.config')();
var gulp = require('gulp');
var del = require('del');
var sass = require('gulp-sass');
var $ = require('gulp-load-plugins')({lazy: true});
var runSequence = require('run-sequence');

var buildProd = true;

/* ============= WATCH =================== */
gulp.task('watch', function() {
  $.watch(config.webrootDir + 'js/**/*.js', $.batch(function (events, done) {
        gulp.start('build-js', done);
    }));
  $.watch(config.webrootDir + 'js/**/*.html', $.batch(function (events, done) {
        gulp.start('build-html', done);
    }));
  $.watch(config.webrootDir + 'css/**/*.css', $.batch(function (events, done) {
        gulp.start('build-css', done);
    }));
  $.watch(config.webrootDir + 'scss/*.scss', $.batch(function (events, done) {
        gulp.start('build-css', done);
    }));
});

/* ============= HTML =================== */

// Build html map for jsApp
gulp.task('html-jsApp-build', function() {
  var files = gulp.src( config.html.jsApp.srcFiles, {read: false});
  return $.file( config.html.jsApp.mapFileName , "//start \n //end", { src: true } )
    .pipe( injectHtmlTemplateIndex( files ) )
    .pipe( gulp.dest( config.webAssetsDir + 'js/') );
});


// Build html map for publicApp
gulp.task('html-publicApp-build', function() {
  var files = gulp.src( config.html.publicApp.srcFiles, {read: false});
  return $.file( config.html.publicApp.mapFileName , "//start \n //end", { src: true } )
    .pipe( injectHtmlTemplateIndex( files ) )
    .pipe( gulp.dest( config.webAssetsDir + 'js/') );
});

// Build file of jsApp HTML templates in script tags for caching
gulp.task('html-jsApp-templates-build', function() {
    var files = gulp.src( config.html.jsApp.srcFiles );
    return $.file('jsapp_html_templates.php' ,"<!-- HTML Templates --> \n <!-- END: HTML Templates -->", { src: true })
      .pipe(gulp.dest( config.webAssetsDir + 'templates/' ))
      .pipe(injectHtmlTemplates(files) )
      .pipe(gulp.dest(config.webAssetsDir + 'templates/'));
});


// Build file of publicApp HTML templates in script tags for caching
gulp.task('html-publicApp-templates-build', function() {
    var files = gulp.src( config.html.publicApp.srcFiles );
    return $.file('publicapp_html_templates.php' ,"<!-- HTML Templates --> \n <!-- END: HTML Templates -->", { src: true })
      .pipe(gulp.dest(config.webAssetsDir + 'templates/' ))
      .pipe(injectHtmlTemplates(files) )
      .pipe(gulp.dest(config.webAssetsDir + 'templates/' ));
});

/* ============ CSS ==================== */

gulp.task('vendor-css-build', function() {
  return gulp.src(config.css.vendor.srcFiles, { base: process.cwd() })
    .pipe($.order(config.css.vendor.srcFiles))
    .pipe($.sourcemaps.init())
    .pipe($.concat('vendor.min.css'))
    .pipe($.minifyCss({compatibility: 'ie8'}))
    .pipe($.sourcemaps.write('../maps'))
    .pipe(gulp.dest(config.webAssetsDir + 'css/'))
    .pipe($.size({title: "vendor.min.css"}));
});

gulp.task('jsApp-css-build', function() {
  return gulp.src(config.css.jsApp.srcFiles, { base: process.cwd() })
    .pipe($.order(config.css.jsApp.srcFiles))
    .pipe($.sourcemaps.init())
    .pipe(sass())
    .pipe($.concat('jsapp.min.css'))
    .pipe($.minifyCss({compatibility: 'ie8'}))
    .pipe($.sourcemaps.write('../maps'))
    .pipe(gulp.dest(config.webAssetsDir + 'css/'))
    .pipe($.size({title: "jsapp.min.css"}));
});

gulp.task('publicApp-css-build', function() {
  return gulp.src(config.css.publicApp.srcFiles, { base: process.cwd() })
    .pipe($.order(config.css.publicApp.srcFiles))
    .pipe($.sourcemaps.init())
    .pipe($.concat('publicapp.min.css'))
    .pipe($.minifyCss({compatibility: 'ie8'}))
    .pipe($.sourcemaps.write('../maps'))
    .pipe(gulp.dest(config.webAssetsDir + 'css/'))
    .pipe($.size({title: "publicapp.min.css"}));
});

/* ============ JAVASCRIPT ==================== */

gulp.task('jsApp-vendor-js-build', function() {
  return gulp.src(config.js.vendor.jsApp.srcFiles, { base: process.cwd() })
    .pipe($.order(config.js.vendor.jsApp.srcFiles))
    .pipe($.sourcemaps.init())
    .pipe($.concat('jsapp-vendor.min.js'))
    .pipe(gulp.dest(config.webAssetsDir + 'js/jsapp/'))
    .pipe($.uglify())
    .pipe($.sourcemaps.write('../../maps'))
    .pipe(gulp.dest(config.webAssetsDir + 'js/jsapp/'))
    .pipe($.size({title: "jsapp-vendor.min.js"}));
});

gulp.task('jsApp-js-build', function() {
  return gulp.src(config.js.jsApp.srcFiles, { base: process.cwd() })
    .pipe($.order(config.js.jsApp.srcFiles))
    .pipe($.sourcemaps.init())
    .pipe($.concat('jsapp.min.js'))
    .pipe(gulp.dest(config.webAssetsDir + 'js/jsapp/'))
    //.pipe($.uglify())
    .pipe($.sourcemaps.write('../../maps'))
    .pipe(gulp.dest(config.webAssetsDir + 'js/jsapp/'))
    .pipe($.size({title: "jsapp.min.js"}));
});

gulp.task('publicApp-js-build', function() {
  return gulp.src(config.js.publicApp.srcFiles, { base: process.cwd() })
    .pipe($.order(config.js.publicApp.srcFiles))
    .pipe($.sourcemaps.init())
    .pipe($.concat('publicapp.min.js'))
    .pipe(gulp.dest(config.webAssetsDir + 'js/jsappadmin/'))
    .pipe($.uglify())
    .pipe($.sourcemaps.write('../../maps'))
    .pipe(gulp.dest(config.webAssetsDir + 'js/publicapp/'))
    .pipe($.size({title: "publicapp.min.js"}));
});

gulp.task('publicApp-vendor-js-build', function() {
  return gulp.src(config.js.vendor.publicApp.srcFiles, { base: process.cwd() })
    .pipe($.order(config.js.vendor.publicApp.srcFiles))
    .pipe($.sourcemaps.init())
    .pipe($.concat('publicapp-vendor.min.js'))
    .pipe(gulp.dest(config.webAssetsDir + 'js/publicapp/'))
    .pipe($.sourcemaps.write('../../maps'))
    .pipe(gulp.dest(config.webAssetsDir + 'js/publicapp/'))
    .pipe($.size({title: "publicapp-vendor.min.js"}));
});

/* ============= FONTS =================== */

gulp.task('vendor-fonts-copy', function () {
  return gulp.src(config.fonts.vendor.srcFiles)
    .pipe($.flatten())
    .pipe(gulp.dest(config.webAssetsDir + 'fonts/'));
});

/* ============= INJECT =================== */

gulp.task('jsApp-inject', function(){
  var jsFiles = gulp.src(config.js.jsApp.injectOrder , {read: false});
  return $.file('jsapp_assets_map.json' ,"{\n\"js\": [\n] \n}", { src: true })
  .pipe( gulp.dest(config.webAssetsDir) )
  .pipe( injectAssetsMap( jsFiles , "js") )
  .pipe(gulp.dest(config.webAssetsDir));
});

gulp.task('publicApp-inject', function(){
  var jsFiles = gulp.src(config.js.publicApp.injectOrder , {read: false});
  return $.file('publicapp_assets_map.json' ,"{\n\"js\": [\n] \n}", { src: true })
  .pipe( gulp.dest(config.webAssetsDir) )
  .pipe( injectAssetsMap( jsFiles , "js") )
  .pipe(gulp.dest(config.webAssetsDir));
});

/* ============= CLEAN =================== */
gulp.task('clean', function (cb) {
  del(config.clean, cb);
});

/* ========================
    TASKS
  ======================== */

  gulp.task('build-html', function(cb) {
    runSequence(
      ['html-jsApp-build', 'html-publicApp-build'],
      ['html-jsApp-templates-build', 'html-publicApp-templates-build'],
    cb);
  });
  gulp.task('build-css', ['jsApp-css-build', 'publicApp-css-build', 'vendor-css-build']);

  gulp.task('build-js', [
    'jsApp-js-build','jsApp-vendor-js-build',
    'publicApp-js-build', 'publicApp-vendor-js-build',
  ]);
  gulp.task('build-fonts', ['vendor-fonts-copy']);

  gulp.task('build', function(cb) {
    runSequence(
      'clean', 'build-html',
      ['build-css', 'build-js', 'build-fonts'],
      'jsApp-inject', 'publicApp-inject',
    cb);
  });

  gulp.task('build-prod', ['build'], function(cb){
    del( config.webAssetsDir + 'maps/' , cb);
  });

  gulp.task('default', ['watch']);

  // Inject assets in JSON for .cpt template pages
  function injectAssetsMap(files, extension, isRelative) {
    isRelative = typeof isRelative !== 'undefined' ? isRelative : true;
    return $.inject(files, {
      relative: isRelative,
      starttag: '"'+extension+'": [',
      endtag: ']',
      transform: function (filepath, file, i, length) {
        return '  "assets/' + filepath + '"' + (i + 1 < length ? ',' : '');
      }
    });
  }

  // Pattern for injecting HTML templates into script tags for front end caching
  function injectHtmlTemplates(files) {
    return $.inject( files, {
      relative: true,
      starttag: '<!-- HTML Templates -->',
      endtag: '<!-- END: HTML Templates -->',
      transform: function (filepath, file, i, length) {
        var contents = file.contents.toString();
        var filename = file.path.match(/([^\/]+)(?=\.\w+$)/)[0];
        return "<script type=\"text/x-jsrender\" id=\""+filename+"\">\n"+contents+"\n</script>\n";
      }
    });
  }

  // Build Index for HTML template reference
  function injectHtmlTemplateIndex(files) {
    return $.inject(files, {
      starttag: '//start',
      endtag: '//end',
      transform: function (filepath, file, i, length) {
        var file = filepath.match(/([^\/]+)(?=\.\w+$)/)[0];
        return 'App.tmpl.' + file + ' = \'#' + file +'\';';
      }
    });
  }

  /**
   * Log a message or series of messages using chalk's blue color.
   * Can pass in a string, object or array.
   */
  function log(msg) {
      if (typeof(msg) === 'object') {
          for (var item in msg) {
              if (msg.hasOwnProperty(item)) {
                  $.util.log($.util.colors.yellow(msg[item]));
              }
          }
      } else {
          $.util.log($.util.colors.yellow(msg));
      }
  }


module.exports = gulp;
