<?php

class Kint_Js_Renderer extends Kint_Renderer
{
    const RENDER_MODE = 'js';

    /**
     * @var array variable name to store JS dumps in
     *
     * false to disable storage
     */
    public static $dump_storage = 'window.kintDump';

    public function render(Kint_Object $o)
    {
        if (self::$dump_storage) {
            return self::$dump_storage.'.push('.json_encode($this->simplify($o)).');console.log('.self::$dump_storage.'['.self::$dump_storage.'.length-1]);';
        } else {
            return 'console.log('.json_encode($this->simplify($o)).');';
        }
    }

    private function simplify(Kint_Object $o)
    {
        if (in_array('recursion', $o->hints)) {
            return 'RECURSION';
        } elseif (in_array('depth_limit', $o->hints)) {
            return 'DEPTH LIMIT';
        } elseif (in_array('blacklist', $o->hints)) {
            return 'BLACKLIST';
        } elseif (is_array($o->value->contents)) {
            $ret = array();
            foreach ($o->value->contents as $child) {
                $ret[$child->name] = $this->simplify($child);
            }

            if ($o->type !== 'array') {
                $ret = (object) $ret;
            }

            return $ret;
        } else {
            return $o->value->contents;
        }
    }

    public function preRender()
    {
        if (self::$dump_storage === false) {
            return '<script>';
        } else {
            return '<script>if(typeof '.self::$dump_storage.'==="undefined")'.self::$dump_storage.'=[];';
        }
    }

    public function postRender()
    {
        return '</script>';
    }
}
