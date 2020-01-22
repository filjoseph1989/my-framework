module.exports = {
    syntax: 'postcss-scss',
    plugins: [
        require('postcss-import'),
        require('autoprefixer'),
        require('postcss-nested'),
        require('tailwindcss')
    ]
}
