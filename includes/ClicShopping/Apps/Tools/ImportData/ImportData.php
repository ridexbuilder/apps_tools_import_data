<?php
/*
 * ImportData.php
 * @copyright Copyright 2008 - http://www.innov-concept.com
 * @Brand : ClicShopping(Tm) at Inpi all right Reserved
 * @license GPL 2 License & MIT Licencse

*/

  namespace ClicShopping\Apps\Tools\ImportData;

  use ClicShopping\OM\Registry;
  use ClicShopping\OM\CLICSHOPPING;

  class ImportData extends \ClicShopping\OM\AppAbstract {

    protected $api_version = 1;
    protected $identifier = 'ClicShopping_ImportData_V1';

    protected function init() {
    }

    public function getConfigModules() {
      static $result;

      if (!isset($result)) {
        $result = [];

        $directory = CLICSHOPPING::BASE_DIR . 'Apps/Tools/ImportData/Module/ClicShoppingAdmin/Config';

        if ($dir = new \DirectoryIterator($directory)) {
          foreach ($dir as $file) {
            if (!$file->isDot() && $file->isDir() && is_file($file->getPathname() . '/' . $file->getFilename() . '.php')) {
               $class = 'ClicShopping\Apps\Tools\ImportData\Module\ClicShoppingAdmin\Config\\' . $file->getFilename() . '\\' . $file->getFilename();

              if (is_subclass_of($class, 'ClicShopping\Apps\Tools\ImportData\Module\ClicShoppingAdmin\Config\ConfigAbstract')) {
                $sort_order = $this->getConfigModuleInfo($file->getFilename(), 'sort_order');
                if ($sort_order > 0) {
                  $counter = $sort_order;
                } else {
                  $counter = count($result);
                }

                while (true) {
                  if (isset($result[$counter])) {
                    $counter++;

                    continue;
                  }

                  $result[$counter] = $file->getFilename();

                  break;
                }
              } else {
                trigger_error('ClicShopping\Apps\Tools\ImportData\ImportData::getConfigModules(): ClicShopping\Apps\Tools\ImportData\Module\ClicShoppingAdmin\Config\\' . $file->getFilename() . '\\' . $file->getFilename() . ' is not a subclass of ClicShopping\Apps\Tools\ImportData\Module\ClicShoppingAdmin\Config\ConfigAbstract and cannot be loaded.');
              }
            }
          }

          ksort($result, SORT_NUMERIC);
        }
      }

      return $result;
    }

    public function getConfigModuleInfo($module, $info)  {
      if (!Registry::exists('ImportDataAdminConfig' . $module)) {
        $class = 'ClicShopping\Apps\Tools\ImportData\Module\ClicShoppingAdmin\Config\\' . $module . '\\' . $module;

        Registry::set('ImportDataAdminConfig' . $module, new $class);
      }

     return Registry::get('ImportDataAdminConfig' . $module)->$info;
    }


    public function getApiVersion()  {
      return $this->api_version;
    }

    public function getIdentifier() {
      return $this->identifier;
    }
  }
