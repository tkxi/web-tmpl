// [Definition] ==================================================
// Development Flag
const mode = require('gulp-mode')({
  modes: ['production', 'development'],
  default: 'development',
  verbose: false
});
let production = mode.production();
console.log(production);

// Task Runner
const gulp = require('gulp');

// Pug
const pug  = require('gulp-pug');

// CSS
const sass         = require('gulp-sass')(require('sass'));
const postcss      = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssSorter    = require('css-declaration-sorter');
const mqpacker     = require("css-mqpacker");

// JS
const babel  = require("gulp-babel");
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');

// Image ver.7.1.0
const imagemin = require('gulp-imagemin');

// Util
const plumber = require('gulp-plumber');
const del     = require('del');
const wait    = require('gulp-wait');

// [Path] ==================================================
const themedir = '_wp/wp-content/themes/xxxxxxxxxx/';
const htdocs = {
  'root' : 'htdocs/',
  'theme': 'htdocs/' + themedir,
  'css'  : 'htdocs/' + themedir + 'assets/css/',
  'font' : 'htdocs/' + themedir + 'assets/font/',
  'js'   : 'htdocs/' + themedir + 'assets/js/',
  'img'  : 'htdocs/' + themedir + 'assets/img/',
};

// Directory: Destination
const dest = {
  'root' : 'dist/',
  'html' : 'dist/',
  'theme': 'dist/' + themedir,
  'css'  : 'dist/' + themedir + 'assets/css/',
  'font' : 'dist/' + themedir + 'assets/font/',
  'js'   : 'dist/' + themedir + 'assets/js/',
  'img'  : 'dist/' + themedir + 'assets/img/'
};

// Directory: Source
const src = {
  'root' : 'src/',
  'copy' : 'src/static/**/*',
  'font' : 'src/font/**/*.{svg,ttf,eot,woff,otf}',
  'pug'  : ['src/pug/**/*.pug', '!src/pug/**/_*.pug'],
  'pugw' : 'src/pug/**/*.pug',
  'php'  : 'src/php/**/*',
  'css'  : ['src/css/**/*.scss', '!src/css/**/-*.scss'],
  'img'  : 'src/img/**/*.{png,jpg,gif,svg}',
  'jslib': [
    'src/js/lib/jquery.min.js',
    'src/js/lib/**/*.js',
    '!src/js/lib/**/-*.js',
  ],
  'jsCommon'   : [
    'src/js/common/*.js',
    '!src/js/**/-*.js',
    '!src/js/lib/**/*.js',
  ],
  'js'   : [
    'src/js/**/*.js',
    '!src/js/**/-*.js',
    '!src/js/common/**/*.js',
    '!src/js/lib/**/*.js',
  ]
};

// [Delete] ==================================================
const fnClean = () => {
  return del([
    dest.root,
    htdocs.theme
  ]);
}

// [Copy] ==================================================
// static copy
const fnCopy = () => {
  return gulp
    .src(src.copy, {
      since: gulp.lastRun(fnCopy),
      dot: true
    })
    .pipe(gulp.dest(dest.root))
    .pipe(gulp.dest(htdocs.root));
}

// copy to htdocs directory
const fnCopyDist = () => {
  return gulp
    .src([
      dest.root + '**/*',
      '!' + dest.root + '**/*.html'
    ])
    .pipe(gulp.dest(htdocs.root));
}

// [Font] ==================================================
const fnFont = () => {
  return gulp
    .src(src.font, {
      since: gulp.lastRun(fnFont),
      dot: true
    })
    .pipe(gulp.dest(dest.font))
    .pipe(gulp.dest(htdocs.font));
}

// [Pug] ==================================================
const fnPug = () => {
  const locals = {
  };
  return gulp
    .src(src.pug)
    .pipe(plumber())
    .pipe(pug({
      locals: locals,
      basedir: 'src',
      pretty: true
    }))
    .pipe(gulp.dest(dest.html));
}

// [CSS] ==================================================
const plugin = [
  autoprefixer(),
  cssSorter({
    order: 'smacss'
  }),
  mqpacker({ sort: true })
];

const fnCss = () => {
  return gulp
    .src(src.css, { sourcemaps: !production })
    .pipe(wait(500))
    .pipe(plumber())
    .pipe(sass({
      // outputStyle: 'compressed'
      outputStyle: 'expanded'
    }).on('error', sass.logError))
    .pipe(postcss(plugin))
    .pipe(gulp.dest(dest.css, { sourcemaps: './' }))
    .pipe(gulp.dest(htdocs.css, { sourcemaps: './' }));
}

// [JS] ==================================================
const fnJsLib = () => {
  return gulp
    .src(src.jslib, { sourcemaps: !production })
    .pipe(concat('lib.js'))
    .pipe(gulp.dest(dest.js, { sourcemaps: './' }))
    .pipe(gulp.dest(htdocs.js, { sourcemaps: './' }));
}

const fnJs = () => {
  return gulp
    .src(src.jsCommon, {
      sourcemaps: !production
    })
    .pipe(concat('script.js'))
    .pipe(babel({
      presets: ['@babel/preset-env']
    }))
    // .pipe(uglify({output: { comments: /^!/ }}))
    .pipe(gulp.dest(dest.js, { sourcemaps: './' }))
    .pipe(gulp.dest(htdocs.js, { sourcemaps: './' }));
}

const fnJsPage = () => {
  return gulp
    .src(src.js, {
      sourcemaps: !production
    })
    .pipe(babel({
      presets: ['@babel/preset-env']
    }))
    .pipe(gulp.dest(dest.js))
    .pipe(gulp.dest(dest.js, { sourcemaps: './' }))
    .pipe(gulp.dest(htdocs.js, { sourcemaps: './' }));
}

// [Image] ==================================================
const imgconf = [
  imagemin.gifsicle({interlaced: true}),
  imagemin.mozjpeg({quality: 90, progressive: true}),
  imagemin.optipng({optimizationLevel: 1}),//設定を変えると、書き出しが遅くなる
  imagemin.svgo({
    plugins: [
      {removeViewBox: false},
      {cleanupIDs: false}
    ]
  })
];

const fnImage = () => {
  return gulp
    .src(src.img, { since: gulp.lastRun(fnImage) })
    .pipe(wait(500))
    .pipe(plumber())
    .pipe(imagemin(imgconf))
    .pipe(gulp.dest(dest.img))
    .pipe(gulp.dest(htdocs.img));
}

// [PHP (Wordpress)] ==================================================
const fnPhp = () => {
  return gulp
    .src(src.php, { since: gulp.lastRun(fnPhp) })
    .pipe(gulp.dest(dest.theme))
    .pipe(gulp.dest(htdocs.theme));
}

// [Watch] ==================================================
const fnWatch = () => {
  gulp.watch(src.copy, fnCopy);
  gulp.watch(src.font, fnFont);
  gulp.watch(src.pugw, fnPug);
  gulp.watch(src.css, fnCss);
  gulp.watch(src.js, gulp.parallel(fnJsLib, fnJs));
  gulp.watch(src.img, fnImage);
  gulp.watch(src.php, fnPhp);
}

// [Tasks] ==================================================
exports.clean    = fnClean;
exports.copy     = fnCopy;
exports.copydist = fnCopyDist;
exports.font     = fnFont;
exports.pug      = fnPug;
exports.css      = fnCss;
exports.jslib    = fnJsLib;
exports.js       = fnJs;
exports.jspage   = fnJsPage;
exports.image    = fnImage;
exports.php      = fnPhp;
exports.watch    = fnWatch;
exports.makefile = gulp.parallel(fnCopy, fnFont, fnPug, fnCss, fnJsLib, fnJs, fnJsPage, fnImage, fnPhp);
exports.build    = gulp.series(fnClean, fnCopy, fnFont, fnPug, fnCss, fnJsLib, fnJs, fnJsPage, fnImage, fnPhp);
exports.default  = gulp.series(fnClean, fnCopy, fnFont, fnPug, fnCss, fnJsLib, fnJs, fnJsPage, fnImage, fnPhp, fnWatch);
