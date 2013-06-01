<?php
namespace Asphxia\Batchio\ImporterDrivers;
interface ImporterInterface {
    public function import($filename);
}