<?php
namespace Asphxia\Batchio\Importer\Drivers;
interface DriverInterface {
    public function import($filename);
}