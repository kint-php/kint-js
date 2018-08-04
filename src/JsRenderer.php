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

namespace Kint\Renderer;

use Kint\Object\BasicObject;

class JsRenderer extends Renderer
{
    const RENDER_MODE = 'js';

    /**
     * @var false|string variable name to store JS dumps in
     *
     * false to disable storage
     */
    public static $dump_storage = 'window.kintDump';

    public function render(BasicObject $o)
    {
        if (self::$dump_storage) {
            return self::$dump_storage.'.push('.\json_encode($this->simplify($o)).');console.log('.self::$dump_storage.'['.self::$dump_storage.'.length-1]);';
        }

        return 'console.log('.\json_encode($this->simplify($o)).');';
    }

    public function renderNothing()
    {
        if (self::$dump_storage) {
            return self::$dump_storage.'.push(undefined);console.log(undefined);';
        }

        return 'console.log(undefined);';
    }

    public function preRender()
    {
        if (false === self::$dump_storage) {
            return '<script>';
        }

        return '<script>if(typeof '.self::$dump_storage.'==="undefined")'.self::$dump_storage.'=[];';
    }

    public function postRender()
    {
        return '</script>';
    }

    private function simplify(BasicObject $o)
    {
        if (\in_array('recursion', $o->hints, true)) {
            return 'RECURSION';
        }
        if (\in_array('depth_limit', $o->hints, true)) {
            return 'DEPTH LIMIT';
        }
        if (\in_array('blacklist', $o->hints, true)) {
            return 'BLACKLIST';
        }
        if (\is_array($o->value->contents)) {
            $ret = array();
            foreach ($o->value->contents as $child) {
                $ret[$child->name] = $this->simplify($child);
            }

            if ('array' !== $o->type) {
                $ret = (object) $ret;
            }

            return $ret;
        }

        return $o->value->contents;
    }
}
