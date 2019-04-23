<?php
/** @var array $options */

$exists = $chunks = false;
$output = null;
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
        if (!empty($options['attributes']['ADDONS'])) {
            $checkboxes = array();
            foreach ($options['attributes']['ADDONS'] as $k => $addons) {
                if (isset($addons['packages']) && !empty($addons['packages'])) {
                    foreach($addons['packages'] as $k => $v) {
                        $checkboxes[] = $k;
                    }
                }
            }
            sort($checkboxes, SORT_NATURAL | SORT_FLAG_CASE);
            $chunks = '<ul id="formCheckboxes" style="height:200px;overflow:auto; padding-top: 10px;">';
            $chunks .= '<input type="hidden" name="install_addons[]" value="">';
            $cols = array();
            $i = $col = 0;
            foreach ($checkboxes as $checkbox) {
                if (!isset($cols[$col])) {
                    $cols[$col] = array();
                }
                if (!in_array($checkbox, array(
                        /* 'CKEditor', */
                        'TinyMCE Rich Text Editor',
                        'tagElementPlugin',
                        'frontendManager',
                        'SmushIt',
                    ))) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                $cols[$col][] = '
                    <li style="width: 45%; margin: 2px 5% 0 0; float: left;">
                        <label>
                            <input type="checkbox" name="install_addons[]" ' . $checked . ' value="' . $checkbox . '"> ' . $checkbox . '
                        </label>
                    </li>';
                $i++;
                if ($i >= count($checkboxes) / 2 ) {
                    $col = 1;
                }
            }
            for ($i = 0; $i <= count($cols[0]); $i++) {
                $chunks .= $cols[0][$i];
                if (isset($cols[1][$i])) {
                    $chunks .= $cols[1][$i];
                }
            }
            $chunks .= '</ul>';
        }
        break;

    case xPDOTransport::ACTION_UPGRADE:
        break;

    case xPDOTransport::ACTION_UNINSTALL:
        break;
}

$output = '';
if ($chunks) {

    switch ($modx->getOption('cultureKey')) {
        case 'ru':
            $output .= 'Выберите дополнения, которые нужно <b>установить</b>:<br/>
				<small>
					<a href="#" onclick="Ext.get(\'formCheckboxes\').select(\'input\').each(function(v) {v.dom.checked = true;});">отметить все</a> |
					<a href="#" onclick="Ext.get(\'formCheckboxes\').select(\'input\').each(function(v) {v.dom.checked = false;});">cнять отметки</a>
				</small>
			';
            break;
        default:
            $output .= 'Select addons, which need to <b>install</b>:<br/>
				<small>
					<a href="#" onclick="Ext.get(\'formCheckboxes\').select(\'input\').each(function(v) {v.dom.checked = true;});">select all</a> |
					<a href="#" onclick="Ext.get(\'formCheckboxes\').select(\'input\').each(function(v) {v.dom.checked = false;});">deselect all</a>
				</small>
			';
    }

    $output .= $chunks;
}
return $output;