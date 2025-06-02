<?php

namespace PluginName;

/*
    Copyright (C) 2025  Derek Kaser

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

$tr = $tr ?? new Translator();

if ( ! defined(__NAMESPACE__ . '\PLUGIN_NAME')) {
    throw new \RuntimeException("PLUGIN_NAME not defined");
}

$usage_cfg     = parse_ini_file("/boot/config/plugins/" . PLUGIN_NAME . "/usage.cfg", false, INI_SCANNER_RAW) ?: array();
$usage_allowed = $usage_cfg['usage_allowed'] ?? "yes";
?>

<h3><?= $tr->tr("metrics.metrics"); ?></h3>

<form method="POST" action="/update.php" target="progressFrame">
<input type="hidden" name="#file" value="/boot/config/plugins/<?= PLUGIN_NAME; ?>/usage.cfg">

<dl>
        <dt><?= $tr->tr("metrics.usage"); ?></dt>
        <dd>
			<select name="usage_allowed" size="1">
				<?= Utils::make_option($usage_allowed, "yes", $tr->tr("yes"));?>
				<?= Utils::make_option($usage_allowed, "no", $tr->tr("no"));?>
			</select>
			<input type="submit" value='<?= $tr->tr("apply"); ?>'>
        </dd>
    </dl>
    <blockquote class='inline_help'><?= $tr->tr("metrics.desc"); ?></blockquote>
</form>
</div>