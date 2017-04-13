<?php

if (!function_exists('j')) {
    /**
     * Alias of Kint::dump(), however the output is dumped to the javascript console and
     * added to the global array `kintDump`. If run in CLI mode, output is pure whitespace.
     *
     * To force rendering mode without autodetecting anything:
     *
     *  Kint::$enabled_mode = Kint::MODE_JS;
     *  Kint::dump( $variable );
     *
     * @return string
     */
    function j()
    {
        if (!Kint::$enabled_mode) {
            return 0;
        }

        $stash = Kint::settings();

        if (Kint::$enabled_mode !== Kint::MODE_TEXT) {
            Kint::$enabled_mode = Kint_Js_Renderer::RENDER_MODE;
            if (PHP_SAPI === 'cli' && Kint::$cli_detection === true) {
                Kint::$enabled_mode = Kint::MODE_CLI;
            }
        }

        $args = func_get_args();
        $out = call_user_func_array(array('Kint', 'dump'), $args);

        Kint::settings($stash);

        return $out;
    }

    Kint::$aliases[] = 'j';
}
