<?php

/*
 * JS renderer for Kint
 * Copyright (C) 2016 Jonathan Vollebregt
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

use Kint\Kint;
use Kint\Renderer\JsRenderer;

/*
 * JS renderer for Kint
 * Copyright (C) 2016 Jonathan Vollebregt
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if (!\function_exists('j')) {
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

        $stash = Kint::$enabled_mode;

        if (Kint::MODE_TEXT !== Kint::$enabled_mode) {
            Kint::$enabled_mode = JsRenderer::RENDER_MODE;
            if (PHP_SAPI === 'cli' && true === Kint::$cli_detection) {
                Kint::$enabled_mode = Kint::MODE_CLI;
            }
        }

        $args = \func_get_args();
        $out = \call_user_func_array(array('Kint', 'dump'), $args);

        Kint::$enabled_mode = $stash;

        return $out;
    }

    Kint::$aliases[] = 'j';
}
