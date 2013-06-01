<?php
namespace Asphxia\Batchio\Importer\Drivers;
interface ImporterInterface {
    public function setInput($input);
    public function process();
}