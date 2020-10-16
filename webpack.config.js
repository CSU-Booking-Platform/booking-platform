const path = require('path')
const webpack = require('webpack')

module.exports = {
    resolve: {
        extensions: ['.js', '.vue'],
        alias: {
            '@src': path.resolve(__dirname, './resources/js'),
            '@test': path.resolve(__dirname, './tests/Javascript')
        }
    },
}
