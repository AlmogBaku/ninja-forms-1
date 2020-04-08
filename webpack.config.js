const defaultConfig = require("./node_modules/@wordpress/scripts/config/webpack.config");
const path = require('path');
const isProduction = "production" === process.env.NODE_ENV;

/**
 * webpack config for blocks.
 *
 * See: https://www.npmjs.com/package/@wordpress/scripts#advanced-usage
 */
module.exports = {
    mode: isProduction ? 'production' : 'development',
    ...defaultConfig,
    entry: {
        'sub-table-block': path.resolve( process.cwd(), 'blocks/views/src/sub-table-block.js'),
        'sub-table-render': path.resolve( process.cwd(), 'blocks/views/src/sub-table-render'),
        'form-block': path.resolve( process.cwd(), 'blocks/form/index.js'),

    },
    output: {
        filename: '[name].js',
        path: __dirname + '/build'
    }
};
