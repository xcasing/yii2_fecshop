<?php
/*
 * 存放 一些基本的非数据库数据 如 html
 * 都是数组
 */

namespace fecshop\app\apphtml5\modules\cms\block\home;

use Yii;

class Index
{
    public function getLastData()
    {
        $this->initHead();
        // change current layout File.
        //Yii::$service->page->theme->layoutFile = 'home.php';
        return [
            //'bestSellerProducts' => $this->getBestSellerProduct(),
            'bestFeaturedProducts'     => $this->getFeaturedProduct(),
            'currency'            => Yii::$service->page->currency->getCurrencyInfo(),
            'currencys'            => Yii::$service->page->currency->getCurrencys(),
            'currentStore'        => Yii::$service->store->currentStore,
            'stores'            => Yii::$service->store->getStoresLang(),
            'currentBaseUrl'    => Yii::$service->url->getCurrentBaseUrl(),
        ];
    }

    public function getFeaturedProduct()
    {
        $featured_skus = Yii::$app->controller->module->params['homeFeaturedSku'];

        return $this->getProductBySkus($featured_skus);
    }

    //public function getBestSellerProduct(){
    //	$best_skus = Yii::$app->controller->module->params['homeBestSellerSku'];
    //	return $this->getProductBySkus($best_skus);
    //}

    public function getProductBySkus($skus)
    {
        if (is_array($skus) && !empty($skus)) {
            $filter['select'] = [
                'sku', 'spu', 'name', 'image',
                'price', 'special_price',
                'special_from', 'special_to',
                'url_key', 'score',
            ];
            $filter['where'] = ['in', 'sku', $skus];
            $products = Yii::$service->product->getProducts($filter);
            //
            $products = Yii::$service->category->product->convertToCategoryInfo($products);
            //var_dump($products);
            return $products;
        }
    }

    public function initHead()
    {
        $home_title = Yii::$app->controller->module->params['home_title'];
        $home_meta_keywords = Yii::$app->controller->module->params['home_meta_keywords'];
        $home_meta_description = Yii::$app->controller->module->params['home_meta_description'];

        Yii::$app->view->registerMetaTag([
            'name' => 'keywords',
            'content' => Yii::$service->store->getStoreAttrVal($home_meta_keywords, 'home_meta_keywords'),
        ]);

        Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => Yii::$service->store->getStoreAttrVal($home_meta_description, 'home_meta_description'),
        ]);
        Yii::$app->view->title = Yii::$service->store->getStoreAttrVal($home_title, 'home_title');
    }
}
