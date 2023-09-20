import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './app/Forms/**/*.php',
        './app/View/**/*.php',
        './resources/views/components/**/*.blade.php',
        './resources/views/errors/**/*.blade.php',
        './resources/views/filament/**/*.blade.php',
        './resources/views/forms/**/*.blade.php',
        './resources/views/vendor/filament/**/*.blade.php',
        './resources/views/vendor/filament-forms/**/*.blade.php',
        './resources/views/vendor/filament-panels/**/*.blade.php',
        './resources/views/vendor/filament-tables/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
