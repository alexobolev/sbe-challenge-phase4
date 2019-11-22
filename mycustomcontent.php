<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

use Symfony\Component\Form\FormBuilderInterface;
use PrestaShopBundle\Form\Admin\Type\SwitchType;

class MyCustomContent extends Module
{
    // ------------------------------------------------------------
    // Module setup:
    //
    //   - __construct()    Module info.
    //
    //   - install()        Hooks, config registration & SQL modifications.
    //                      Calls install*() methods.
    //   - installConfig()
    //   - installHooks()
    //   - installDatabase()
    //
    //   - uninstall()      Config and SQL cleanup.
    //                      Calls uninstall*() methods.
    //   - uninstallConfig()
    //   - uninstallDatabase()
    // ------------------------------------------------------------

    /**
     * Set up module information.
     */
    public function __construct()
    {
        $this->name = 'mycustomcontent';
        $this->tab = 'front_office_features';
        $this->version = '0.1.0';
        $this->author = "Alex Sobolev";
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7',
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l("My Custom Content");
        $this->description = $this->l("Custom content on the product page");
        $this->confirmUninstall = $this->l("Are you sure you want to uninstall?");

        if (!Configuration::get('MYCUSTOMCONTENT_VIEWENABLED') ||
            !Configuration::get('MYCUSTOMCONTENT_CONTENT')
        ) {
            $this->warning = $this->l('Not all MyCustomContent settings provided');
        }
    }

    /**
     * Install the module, calling install*() to do the jobs.
     * @return boolean Whether the installation succeeded.
     */
    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        return parent::install() &&
               $this->installHooks() &&
               $this->installConfig() &&
               $this->installDatabase();
    }

    /**
     * Register the hooks used by the module.
     * @return boolean Whether the hooks were registered successfully.
     */
    public function installHooks()
    {
        $hookNames = [
            'header',                              // custom CSS for front-end
            'displayProductAdditionalInfo',        // the content block in front-end
            'displayAdminProductsOptionsStepTop'   // per-Product config in back-office
        ];

        foreach ($hookNames as $name)
        {
            if (!$this->registerHook($name))
                return false;
        }

        return true;
    }

    /**
     * Set up default configuration values.
     * @return boolean Whether the configuration values were set up successfully.
     */
    public function installConfig()
    {
        $configValues = [
            'MYCUSTOMCONTENT_VIEWENABLED' => true,
            'MYCUSTOMCONTENT_CONTENT' => 'Hello, XXI Century World!'
        ];

        foreach ($configValues as $key => $value)
        {
            if (!Configuration::updateValue($key, $value, true))
                return false;
        }

        return true;
    }

    /**
     * Set up the database extension.
     * Add column `mcc_product_viewenabled` to {$PREFIX}product table.
     * @return boolean Whether the new columns were added successfully.
     */
    public function installDatabase()
    {
        $sqlAddViewEnabled = 'ALTER TABLE ' . _DB_PREFIX_ . 'product ADD mcc_product_viewenabled TINYINT(1) DEFAULT 1;';

        $status = true;
        $status &= Db::getInstance()->execute($sqlAddViewEnabled);

        return $status;
    }

    /**
     * Uninstall the module, calling uninstall*() to do the jobs.
     * @return boolean Whether the uninstallation succeeded.
     */
    public function uninstall()
    {
        return parent::uninstall() &&
               $this->uninstallConfig() &&
               $this->uninstallDatabase();
    }

    /**
     * Tear down configuration values.
     * @return boolean Whether the configuration values were deleted successfully.
     */
    public function uninstallConfig()
    {
        $configKeys = [
            'MYCUSTOMCONTENT_VIEWENABLED',
            'MYCUSTOMCONTENT_CONTENT'
        ];

        foreach ($configKeys as $key)
        {
            if (!Configuration::deleteByName($key))
                return false;
        }

        return true;
    }

    /**
     * Tear down the database extension.
     * Drop column `mcc_viewenabled` from {$PREFIX}product table.
     * @return boolean Whether the database fields were deleted successfully.
     */
    public function uninstallDatabase()
    {
        $sqlDropViewEnabled = 'ALTER TABLE ' . _DB_PREFIX_ . 'product DROP mcc_product_viewenabled;';

        $status = true;
        $status &= Db::getInstance()->execute($sqlDropViewEnabled);

        return $status;
    }


    // ------------------------------------------------------------
    // Hooks:
    //
    //   - hookDisplayHeader()                           Custom module CSS.
    //   - hookDisplayProductAdditionalInfo()            Front-end content block.
    //   - hookDisplayAdminProductsOptionsStepTop(..)    Per-Product settings form in back-office.
    // ------------------------------------------------------------

    public function hookDisplayHeader()
    {
        $this->context->controller->addCSS($this->_path . 'css/mycustomcontent.css', 'all');
    }

    public function hookDisplayProductAdditionalInfo($params)
    {
        $product = $params['product'];

        $viewEnabled = Configuration::get('MYCUSTOMCONTENT_VIEWENABLED') && (bool)$product['mcc_product_viewenabled'];
        $contentString = Configuration::get('MYCUSTOMCONTENT_CONTENT');

        $this->context->smarty->assign([
            'mycustomcontent_viewenabled' => $viewEnabled,
            'mycustomcontent_content' => $contentString
        ]);

        return $this->display(__FILE__, 'mycustomcontent.tpl');
    }

    public function hookDisplayAdminProductsOptionsStepTop(array $params)
    {
        $productId = $params['id_product'];
        $productInstance = new Product($productId);
        
        $this->context->smarty->assign([
            'mycustomcontent_product_debug' => '<pre><code>' . json_encode($productInstance) . '</code></pre>',
            'mycustomcontent_product_checked' => (int)$productInstance->mcc_product_viewenabled
        ]);
        return $this->display(__FILE__, 'mycustomcontentproductoption.tpl');
    }


    // ------------------------------------------------------------
    // Module configuration:
    //
    //   - getContent()    Module configuration page.
    //   - renderForm()    Configuration form contents.
    // ------------------------------------------------------------

    /**
     * Display module configuration page.
     */
    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit' . $this->name)) {
            $mccViewEnabled = boolval(Tools::getValue('MYCUSTOMCONTENT_VIEWENABLED'));
            $mccContent = strval(Tools::getValue('MYCUSTOMCONTENT_CONTENT'));

            if (
                !Validate::isBool($mccViewEnabled) ||
                !Validate::isString($mccContent)
            ) {
                $output .= $this->displayError($this->l('Invalid configuration value'));
            } else {
                Configuration::updateValue('MYCUSTOMCONTENT_VIEWENABLED', $mccViewEnabled, true);
                Configuration::updateValue('MYCUSTOMCONTENT_CONTENT', $mccContent, true);
                $output .= $this->displayConfirmation($this->l('Settings updated'));
            }
        }

        return $output . $this->renderForm();
    }

    /**
     * Make HTML code for the module configuration form.
     * @return string Configuration form's HTML.
     */
    public function renderForm()
    {
        $defaultLang = (int)Configuration::get('PS_LANG_DEFAULT');

        $fieldsForm[0]['form'] = [
            'legend' => [
                'title' => $this->l('Settings'),
            ],
            'input' => [
                [
                    'type' => 'switch',
                    'label' => $this->l('Show on page'),
                    'name' => 'MYCUSTOMCONTENT_VIEWENABLED',
                    'is_bool' => true,
                    'required' => true,
                    'values' => [
                        [
                            'id' => 'MYCUSTOMCONTENT_VIEWENABLED_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id' => 'MYCUSTOMCONTENT_VIEWENABLED_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ]
                    ]
                ],
                [
                    'type' => 'textarea',
                    'label' => $this->l('Content to display'),
                    'name' => 'MYCUSTOMCONTENT_CONTENT',
                    'desc' => $this->l('HTML supported, will be displayed on the product page in a big white box'),
                    'required' => true,
                    'class' => 'rte'
                ]
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-primary pull-right'
            ]
        ];

        $this->context->controller->addJS($this->_path . 'js/tinymce-init.js');

        $helper = new HelperForm();

        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        $helper->default_form_language = $defaultLang;
        $helper->allow_employee_form_lang = $defaultLang;

        $helper->title = $this->displayName;
        $helper->show_toobar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = [
            'save' => [
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'),
            ],
            'back' => [
                'desc' => $this->l('Back to list'),
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules')
            ]
        ];

        $helper->fields_value['MYCUSTOMCONTENT_VIEWENABLED'] = Configuration::get('MYCUSTOMCONTENT_VIEWENABLED');
        $helper->fields_value['MYCUSTOMCONTENT_CONTENT'] = Configuration::get('MYCUSTOMCONTENT_CONTENT');

        return $helper->generateForm($fieldsForm);
    }
}
