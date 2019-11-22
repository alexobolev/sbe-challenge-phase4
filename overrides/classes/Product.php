<?php
class Product extends ProductCore {
    
    public $mcc_product_viewenabled;
    public $mcc_product_overrideenabled;
    public $mcc_product_overridevalue;

    public function __construct($id_product = null, $full = false, $id_lang = null, $id_shop = null, Context $context = null) {
        self::$definition['fields']['mcc_product_viewenabled'] = [
            'type' => self::TYPE_INT
        ];
        self::$definition['fields']['mcc_product_overrideenabled'] = [
            'type' => self::TYPE_INT
        ];
        self::$definition['fields']['mcc_product_overridevalue'] = [
            'type' => self::TYPE_HTML
        ];

        parent::__construct($id_product, $full, $id_lang, $id_shop, $context);
    }
}