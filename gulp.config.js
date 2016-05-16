module.exports = function() {

  var bowerDir = 'bower_components/';

  var webrootDir = 'app/webroot/';
  var webAssetsDir = webrootDir + 'assets/';
  var jsAppDir = webrootDir + 'js/app/';

  var config = {
    // Accessible variables
    webrootDir: webrootDir,
    webAssetsDir: webAssetsDir,
    jsAppDir: jsAppDir,

    clean: [
      webrootDir + 'assets/',
      webrootDir + 'maps/',
      webrootDir + 'jsapp_assets_map.json',
      webrootDir + 'publicapp_assets_map.json',
    ],
    // =============== HTML ==============
    html: {
      // ---- jsApp ---
      jsApp: {
        mapFileName: 'jsapp_templates_map.js',
        srcFiles: [
          jsAppDir + 'tmpl/**/*.html'
        ]
      },
      publicApp: {
        mapFileName: 'publicapp_templates_map.js',
        srcFiles: []
      }
    },
    // =============== CSS ==============
    css: {
      vendor: {
        srcFiles: [
          bowerDir + 'bootstrap/dist/css/bootstrap.css',
					bowerDir + 'bootstrap-select/dist/css/bootstrap-select.css',
          webrootDir + 'css/font-awesome.css'
        ]
      },
      jsApp: {
        srcFiles: [
          webrootDir + 'scss/style.scss',
        ]
      },
      publicApp: {
        srcFiles: [
          webrootDir + 'css/inner/style.css',
        ]
      }
    },
    // =============== JAVASCRIPT ==============
    js: {
      vendor: {
        jsApp: {
          srcFiles: [
            // Core Dependencies
            /*bowerDir + 'json/json2.js',
            bowerDir + 'underscore/underscore.js',
            bowerDir + 'jquery/jquery.js',
            bowerDir + 'backbone/backbone.js',

            bowerDir + 'bootstrap/dist/js/bootstrap.js',
            bowerDir + 'accounting.js/accounting.js',
            bowerDir + 'bootbox/bootbox.js',
            bowerDir + 'backbone.routeFilter/dist/backbone.routeFilter.js',*/

            //bowerDir + 'bootstrap-datepicker/dist/js/bootstrap-datepicker.js',

            //bowerDir + 'jquery-codaslider.js',
            // Rest of files
            //bowerDir + 'accounting.js/accounting.js',
            //bowerDir + 'bootbox/bootbox.js',
            //bowerDir + 'backbone.routeFilter/dist/backbone.routeFilter.js',
            //bowerDir + 'bootstrap-datepicker/dist/js/bootstrap-datepicker.js',

            // Ordered
            webrootDir + 'js/lib/development/json2.js',
            webrootDir + 'js/lib/development/underscore.1.4.4.js',
            webrootDir + 'js/lib/development/jquery.1.9.1.js',
            webrootDir + 'js/lib/development/backbone.1.0.js',
            webrootDir + 'js/lib/development/bootstrap.3.3.5.js',

            // Original Sources
            webrootDir + 'js/lib/development/accounting.js',
            //webrootDir + 'js/lib/development/backbone.1.0.js',
            webrootDir + 'js/lib/development/backbone.routefilter.js',

            webrootDir + 'js/lib/development/bootstrap-datepicker.js',
						bowerDir + 'bootstrap-select/dist/js/bootstrap-select.js',
            //webrootDir + 'js/lib/development/bootstrap.3.3.5.js',
            webrootDir + 'js/lib/development/coda-slider.1.1.1.pack.js.js',
            webrootDir + 'js/lib/development/ddaccordion.js',
            webrootDir + 'js/lib/development/jquery-easing-1.3.pack.js',
            webrootDir + 'js/lib/development/jquery-easing-compatibility.1.2.pack.js',
            //webrootDir + 'js/lib/development/jquery.1.9.1.js',
            webrootDir + 'js/lib/development/jquery.placeholder.js',
            //webrootDir + 'js/lib/development/json2.js',
            webrootDir + 'js/lib/development/jsrender.beta-candidate.37.js',
            webrootDir + 'js/lib/development/moment.js',
            webrootDir + 'js/lib/development/purl.js',
            //webrootDir + 'js/lib/development/underscore.1.4.4.js',
            webrootDir + 'js/lib/development/bootbox.4.4.js',
          ]
        },
        publicApp: {
          srcFiles: [
            /*  bowerDir + 'jquery/jquery.js',
              bowerDir + 'json/json2.js',
              bowerDir + 'underscore/underscore.js',
              bowerDir + 'backbone/backbone.js',
              bowerDir + 'bootstrap/dist/js/bootstrap.js'*/

            // Ordered
            webrootDir + 'js/lib/development/json2.js',
            webrootDir + 'js/lib/development/underscore.1.4.4.js',
            webrootDir + 'js/lib/development/jquery.1.9.1.js',
            webrootDir + 'js/lib/development/jquery.placeholder.js',
            webrootDir + 'js/lib/development/jquery-easing-1.3.pack.js',
            webrootDir + 'js/lib/development/jquery-easing-compatibility.1.2.pack.js',
            webrootDir + 'js/lib/development/bootstrap.3.3.5.js',
            webrootDir + 'js/lib/development/coda-slider.1.1.1.pack.js',
            webrootDir + 'js/lib/development/moment.js',
            webrootDir + 'js/lib/development/purl.js',
            webrootDir + 'js/lib/development/backbone.1.0.js',
            webrootDir + 'js/lib/development/jsrender.beta-candidate.37.js',
          ]
        }
      },
      // ---- jsApp ---
      jsApp: {
        srcFiles: [
          webrootDir + 'js/alloy/alloy.js',
          webrootDir + 'js/alloy/alloy.session.js',
          webrootDir + 'js/alloy/alloy.api.js',
          webrootDir + 'js/alloy/alloy.view.js',
          webrootDir + 'js/alloy/*.js',
          webrootDir + 'js/alloy/*/*.js',
          jsAppDir + 'app.js',
          jsAppDir + 'router_base.js',
          jsAppDir + 'router.js',
          webAssetsDir + 'js/jsapp_templates_map.js',
          jsAppDir + '**/*.js',
        ],
        injectOrder: [
          //'!' + webAssetsDir + 'js/jsApp/jsApp_templates_map.js',
          webAssetsDir + 'js/jsapp/*.js'
        ]
      },
      // ---- publicApp ---
      publicApp: {
        srcFiles: [
          webrootDir + 'js/alloy/alloy.js',
          webrootDir + 'js/alloy/alloy.session.js',
          webrootDir + 'js/alloy/alloy.api.js',
          webrootDir + 'js/alloy/alloy.view.js',
          webrootDir + 'js/alloy/*.js',
          webrootDir + 'js/alloy/**/*.js',
          jsAppDir + 'app.js',
          jsAppDir + 'data.js',
          webAssetsDir + 'js/publicapp_templates_map.js'
        ],
        injectOrder: [
          //'!' + webAssetsDir + 'js/publicApp/publicApp_templates_map.js',
          //webAssetsDir + 'js/publicApp/publicApp-vendor.min.js',
          webAssetsDir + 'js/publicapp/**/*.js'
        ]
      }
    },
    // =============== FONTS ==============
    fonts: {
      vendor: {
        srcFiles: [
          bowerDir + 'bootstrap/dist/fonts/**/*',
          webrootDir + 'font/*'
        ]
      }
    }

  };

  return config;
};
