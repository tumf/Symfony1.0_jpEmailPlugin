<?php

function link_to_file($path, $name = null)
{
  return link_to(($name === null ? $path : $name), 'jpEmail/showFile?filename='.rawurlencode(str_replace(array('\\', '.'), array('/', '%%'), $path)));
}
