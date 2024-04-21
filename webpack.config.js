const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
  .setOutputPath('./src/Resources/public/build/')
  .setPublicPath('./')
  .setManifestKeyPrefix('bundles/optimeemail')
  .addEntry('app', './assets/js/app.js')
  .disableSingleRuntimeChunk()
  .enableBuildNotifications()
  .enableSourceMaps(!Encore.isProduction())
  .configureBabelPresetEnv((config) => {
    config.useBuiltIns = 'usage';
    config.corejs = 3;
  })
  .enableReactPreset()
;

if (Encore.isProduction()) {
  Encore.cleanupOutputBeforeBuild()
}

module.exports = Encore.getWebpackConfig();