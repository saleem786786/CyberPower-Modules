<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Client;

use ModulesGarden\Servers\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\FeaturesSettingsConstants;

class DnsSettings extends AbstractController
{
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent;
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ClassNameComponent;
    
    public function index()
    {
        if ($this->isFeatureEnabled(FeaturesSettingsConstants::DNS) === false)
        {
            return null;
        }

        return Helper\view()
                        ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Customs\Pages\Description($this->getClassName()))
                        ->addElement(\ModulesGarden\Servers\DirectAdminExtended\App\UI\DnsSettings\Pages\DomainsTable::class);
    }
    
    public function showRecords()
    {
        if ($this->isFeatureEnabled(FeaturesSettingsConstants::DNS) === false)
        {
            return null;
        }
        
        return Helper\view()
                        ->addElement(\ModulesGarden\Servers\DirectAdminExtended\App\UI\DnsSettings\Pages\DnsTable::class);
    }
}