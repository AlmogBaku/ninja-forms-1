const defaultConfig = require("./node_modules/@wordpress/scripts/config/webpack.config");
const path = require('path');
const isProduction = "production" === process.env.NODE_ENV;
console.log(isProduction);
module.exports = {
    mode: isProduction ? 'production' : 'development',
    ...defaultConfig,
    entry: {
        'sub-table-block': path.resolve( process.cwd(), 'blocks/views/src/sub-table-block.js'),
        'sub-table-render': path.resolve( process.cwd(), 'blocks/views/src/sub-table-render'),
    },
    output: {
        filename: '[name].js',
        path: __dirname + '/build'
    }
};
