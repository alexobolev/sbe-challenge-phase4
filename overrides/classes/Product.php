<?php
class Product extends ProductCore {
    
    public $mcc_product_viewenabled;

    public function __construct($id_product = null, $full = false, $id_lang = null, $id_shop = null, Context $context = null) {
        self::$definition['fields']['mcc_product_viewenabled'] = [
            'type' => self::TYPE_INT
        ];

        parent::__construct($id_product, $full, $id_lang, $id_shop, $context);
    }
}