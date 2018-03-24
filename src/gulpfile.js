const gulp = require('gulp');
const sass = require('gulp-sass');
const sourcemaps = require('gulp-sourcemaps');
const webpack = require('gulp-webpack');
const Webpack = require('webpack');
const watch = require('gulp-watch');
const argv = require('yargs').argv;

gulp.task('scripts', function () {
    return gulp.src('js/app.js')
        .pipe(webpack({
            module: {
                loaders: [
                    {
                        loader: 'babel-loader',
                        exclude: /node_modules/,
                        query: {
                            presets: ['es2015', 'flow'],
                            plugins: ["transform-decorators-legacy"]
                        }
                    },
                    {
                        include: /\.json$/,
                        loaders: ["json-loader"]
                    }
                ]
            },
            output: {
                filename: 'app.js'
            },
            plugins: argv.env !== "local" ?
                [
                    new Webpack.DefinePlugin({
                        'process.env':{
                            'NODE_ENV': JSON.stringify('production')
                        }
                    }),
                    new Webpack.optimize.UglifyJsPlugin({
                        compress:{
                            warnings: false
                        }
                    })
                ]:
                [],
            devtool: argv.env === "local" ? 'source-map' : ''
        }))
        .pipe(gulp.dest('../assets/'));
});

gulp.task('style', function () {
    if(argv.env === "local") {
        return gulp.src('sass/app.scss')
            .pipe(sourcemaps.init())
            .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
            .pipe(sourcemaps.write("./"))
            .pipe(gulp.dest('../assets/'));
    } else {
        return gulp.src('sass/app.scss')
            .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
            .pipe(gulp.dest('../assets/'));
    }
});

gulp.task('default', ['scripts', 'style']);

gulp.task('watch', function() {
    watch('sass/**/*.scss', function() {
        gulp.start('style');
    });
    watch('js/**/*.js', function() {
        gulp.start('scripts');
    });
});


