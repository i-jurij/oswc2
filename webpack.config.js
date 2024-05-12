const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");

module.exports = {
    entry: './resources/js/app.js',
    mode: (process.env.NODE_ENV === 'production') ? 'production' : 'development',
    resolve: {
        extensions: ['*', '.js', '.jsx']
    },
    output: {
        path: path.join(__dirname, 'www', 'assets'),
        filename: 'js/bundle.js',
        clean: true,
    },
    devServer: {
        static: path.join(__dirname, 'www/'),
        devMiddleware: {
            publicPath: '/assets/'
        },
        port: 3000,
        hot: "only",
        client: {
            // Показывает ошибки при компиляции в самом браузере
            overlay: {
                // Ошибки
                errors: true,
                // Предупреждения
                warnings: false,
            },
            // Показывает процесс компиляции
            progress: true
        },
    },
    /*
    //// load css into DOM (one js file) ////
    module: {
        rules: [
            {
                test: /\.css$/,
                use: ['style-loader', 'css-loader'],
            },
        ],
    },
    //// end load css into DOM (one js file) ////
    */
    //// css as separate file ////
    module: {
        rules: [
            {
                test: /.s?css$/,
                use: [
                    MiniCssExtractPlugin.loader, // CSS to separate file
                    'css-loader', // CSS to CommonJS
                    // 'sass-loader' // for sass, compile SCSS to CSS
                ],
            },
        ],
    },
    optimization: {
        minimizer: [
            new CssMinimizerPlugin({
                parallel: true,
                minimizerOptions: {
                    preset: [
                        "default",
                        {
                            discardComments: { removeAll: true },
                        },
                    ],
                },
            }),
        ],
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: 'css/style.min.css',
        }),
    ],
    //// end css as different file ////
};