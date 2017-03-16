<?php
$checkboxes = explode('||', $input);
return in_array($options, $checkboxes) ? 'true' : 'false';