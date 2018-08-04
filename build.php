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

use Seld\PharUtils\Timestamps;

require_once __DIR__.'/vendor/autoload.php';

\mkdir(__DIR__.'/build');

$outpath = __DIR__.'/build/kint-js.phar';

\unlink($outpath);
$phar = new Phar($outpath);
$phar->setStub('<?php
/*
 * '.\str_replace("\n", "\n * ", \trim(\file_get_contents(__DIR__.'/LICENSE.short'))).'
 */

require \'phar://\'.__FILE__.\'/init.php\'; __HALT_COMPILER();');

$pathlen = \strlen(__DIR__);

$phar->addFile(__DIR__.'/src/JsRenderer.php', '/src/JsRenderer.php');
$phar->addFile(__DIR__.'/init.php', '/init.php');
$phar->addFile(__DIR__.'/init_helpers.php', '/init_helpers.php');

$phar = new Timestamps($outpath);
$phar->updateTimestamps();
$phar->save($outpath, Phar::SHA512);
