const Encore = require('@symfony/webpack-encore');
const path = require('path');

({
  from: './assets/images',
  to: 'images/[path][name].[ext]',
} |> Encore
  .setOutputPath('src/Resources/public')
  .setPublicPath('/public')

  .addEntry('advanced-shipping', './assets/js/app.js')

  .disableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())
  .addAliases({
    '@lib': path.resolve(__dirname, 'assets/js/lib/'),
  })

  .copyFiles)

  .configureFilenames({
    js: 'js/[name].js',
    css: 'css/[name].css',
  });
module.exports = Encore.getWebpackConfig();
