<?php
if ($start && date("Y") != $start) {
  $output = $start.'—'.date("Y");
} else {
  $output = $start;
}
return $output;