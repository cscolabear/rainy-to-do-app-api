<?php

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Product::count() > 0) {
            return;
        }

        Product::unguard();
        Product::insert(
            array_merge(
                $this->data_s1_c1(),
                $this->data_s1_c4()
            )
        );
        Product::reguard();
    }

    private function data_s1_c1()
    {
        return [
            [
                'source_id' => 1,
                'category_id' => 1,
                'title' => '【新北坪林】乘著 SUP 去旅行，北勢溪遊河趣！',
                'description' => '夏天到了，想玩水又對各種水上運動的難度卻步嗎？ 近年來國外掀起了一陣 SUP 立式槳板運動的熱潮，不論年齡、性別，只要教練帶領基本動作、技巧，很快就可以輕鬆上手，享受自由駕馭的成就感。到底什麼是 SUP 呢？簡單說就是一塊衝浪板加上一把槳，玩家就站立在衝浪板上划槳，可以遊河、衝浪，更可以在上面進行瑜珈、體適能等，各種豐富玩法。',
                'link' => '/product/1451',
                'img' => '//d1f5je0f1yvok5.cloudfront.net/photo/u/h/e/uheIlvinwt33qla8nbLDvw_o.jpg',
                'price' => 1200,
            ],
            [
                'source_id' => 1,
                'category_id' => 1,
                'title' => '親子生態觀察 - 基隆八斗子潮境公園',
                'description' => '做為海島子民，我們應該從小多了解台灣美麗的海岸線地型，才會知道豐富生態有多麼的珍貴。趁著兒童連假，帶著孩子來到基隆八斗子潮境公園，探訪這裡都住了哪些可愛的居民？面臨艱困的生存環境，他們又有哪些厲害的本領呢？',
                'link' => '/product/3196',
                'img' => '//d1f5je0f1yvok5.cloudfront.net/photo/n/g/f/ngfJsvXYDNfBjmByMVLULQ_o.jpg',
                'price' => 600,
            ],
        ];
    }

    private function data_s1_c4()
    {
        return [
            [
                'source_id' => 1,
                'category_id' => 4,
                'title' => '一日植物學｜手作浮游花瓶體驗課程',
                'description' => '浮游花不佔空間、也不需要換水，非常輕鬆，並且能夠長時間欣賞花朵的美麗，適合當作擺設、改變氛圍的居家設計，或是送給親朋好友當作禮物，代替活體花束用來祝福身邊的人。體驗嶄新的花卉藝術，一起封藏美麗!創作獨一無二的專屬浮游花瓶 ~',
                'link' => '/product/4368',
                'img' => '//d1f5je0f1yvok5.cloudfront.net/photo/t/I/y/tIy4A5XrrmIY8AKvT7DPcA_s.jpg',
                'price' => 650,
            ],
            [
                'source_id' => 1,
                'category_id' => 4,
                'title' => ': 多肉木器小品 : 禪風與森林綠意的結合',
                'description' => '與其他植物相較，厚實的多肉植物多了種樸拙穩重，透過溫潤的木器及生氣蓬勃的多肉植物，組合出溫暖人心的多肉小品。適合擺在窗邊，跟著多肉追逐陽光、感受微風，為生活點綴森林的氣息，送禮自用兩相宜！',
                'link' => '/product/3729',
                'img' => '//d1f5je0f1yvok5.cloudfront.net/photo/G/d/K/GdKxV_6,AERcQs1883_HTQ_o.jpg',
                'price' => 500,
            ],
            [
                'source_id' => 1,
                'category_id' => 4,
                'title' => '創客精神 - 北歐瘋手作燈工作坊',
                'description' => '自己製作一盞專屬的溫暖光源，將自己生活中「物件」昇華為一件「作品」，讓用心地投入的過程提升生活的質感。在忙碌的生活中空出一點時間，體驗看看手作的樂趣吧！',
                'link' => '/product/1946',
                'img' => '//d1f5je0f1yvok5.cloudfront.net/photo/2/G/H/2GHRSGIhN8UlrdygBlktoQ_s.jpg',
                'price' => 1800,
            ],
        ];
    }
}
