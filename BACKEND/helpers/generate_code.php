<?php
function generate_code($prefix = "TRX") {
  return $prefix . strtoupper(uniqid());
}
?>
