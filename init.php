<?php

if (!class_exists('Kint_Js_Renderer', true)) {
    require_once dirname(__FILE__).'/src/Js.php';
    require_once dirname(__FILE__).'/init_helpers.php';
} elseif (KINT_PHP53 && !Kint::composerGetDisableHelperFunctions()) {
    require_once dirname(__FILE__).'/init_helpers.php';
}

Kint::$renderers[Kint_Js_Renderer::RENDER_MODE] = 'Kint_Js_Renderer';
