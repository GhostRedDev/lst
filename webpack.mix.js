import mix from 'laravel-mix';

// Configuración principal
mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .sourceMaps()
   .version();

// Configuración adicional si necesitas
if (mix.inProduction()) {
    mix.version();
} else {
    mix.sourceMaps();
}