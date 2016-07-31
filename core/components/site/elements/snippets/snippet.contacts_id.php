<?php
if ($contacts = $modx->getObject('modResource', array('alias' => 'contacts', 'parent' => 0))) {
  $output = $contacts->id;
} else {
  $output = $modx->getOption('site_start');
}
return $output;