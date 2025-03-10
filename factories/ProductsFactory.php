<?php

namespace app\factories;

use Yii;
use yii\db\Connection;
use Faker\Factory;

class ProductsFactory
{
    protected $db;
    protected $faker;

    // Массив изображений
    private $images = [
        '1208c01f332e5a95df9383fee80447f502450fe5e79b5bc7ada684a267c64342.jpg',
    '8fa5f1713d25dc12d358a73b355ddb57b38adb578ce1ea6137043cf9948d4aa1.jpg',
    '0cd3bd5c49f169e24a3ebb21cf7c5ba7de0604d65ae27fc1a1ef729ffeb36c97.jpg',
    'd970a804e0bfd3a926bfa4064a5bcce76dbc7855bd472e6c570b32843a6ab504.jpg',
    '308ee9825c35be43894b02cbf38f4d0395d0285a71a137d601bf4f0d75c073a1.jpg',
    'db8d94ea4a75f751cb645e68bbc6725a5ed79bb2f4f8b163f3df9dd826b9dde8.jpg',
    'a9fb3c9dff46f8eee21a5968b0a11072b9f7d36e2834b86cda4eb56f8c1a4e5c.jpg',
    'f4deac5cb0456eb3024702e40628fc0fd0a81a91d6ec79821f2bdaa570a10dd0.jpg',
    'a0905069dffa20d66e0764d4c6c312be166f464357f87e6ac81569206abfb620.png',
    'ae103f2d127ca2c6324c1f840ad3efb054082bb50e8b4eab0bb5654bea076ed8.jpg',
    'f890b75763802ea62752c6c82be3ecd6dac94be02e94aa3a25210dd7344dec5e.jpg',
    'b0c30dafcc4acd96ab57e3fc05e4017a696b2fee546ba061da2acf1a8fd98c65.jpg',
    '4e1b96120d54f342c34685f1222fffdf5b7d5da5e9a5037fd746d75cbc46049a.jpg',
    'c216af6d56fc65193e0d1ee5bae8831db67c7fef33a5ff4caab15c1574b2eedb.jpg',
    '5e7ec8d8ded5acbcfbd4a35f7e52e0a202707eb865eff1aefab6cb669503c037.jpg',
    '10d80964b70ea3edc58905fe348c4c463356140ac19346c5f3163d1148c1ce28.jpg',
    'b32c839e88176aa4241a2add9e0cff6f15be038300184f2f35afc9f662b6e786.jpg',
    'e86f7b605064bf8aa555f8230058c76d97558f896c2366bff5c71ce7d41fe7c9 (2).jpg',
    '30678452e82ee1b634c7edc900911b33861a5f2ba2256f37de71cd2bb67decec.jpg',
    '3fcd47d343e6f250da4ed7f51dc3b4e1cbc976c199781fb57992114a7a4db9b4.jpg',
    '5808ac28af89a0c231c83135ea62216b2244d0a43b2103e49952cedbf3949f0c.jpg',
    '0fcec85df38f2694ea63087958ac2e779ef3e27e9eed0b3ce2433dea7dd978d8.jpg',
    'e86f7b605064bf8aa555f8230058c76d97558f896c2366bff5c71ce7d41fe7c9 (1).jpg',
    '19f40d51d2474d63db0ca6ce8fba30774d2f6f5a2ebc7a8c36bd5041de530c48.jpg',
    'e86f7b605064bf8aa555f8230058c76d97558f896c2366bff5c71ce7d41fe7c9.jpg',
    'c2e3bbd1f73f30ba136fe085d1f5f68ff0c3eb718e80d46f1e93dee1036f241e.jpg',
    '75cbfcb106a4b3c3c2caaeb61d03e10a0ae515caa81a9b74cc6cf373ca75130b.jpg',
    'a001e769b88eb123179dbefc6385367278ae765aac62c5f6c46a6b3aac32d126.jpg',
    '49653983e1254d02a3270dc481cdff30fd22ce660bc2be88eed4f1f84bd56dc9.jpg',
    '89cc1a3b456c1fc91be39299826c57cf48d79c3e74cb7066f437c6fdc9348aed.jpg',
    '1d3d4e06f541f4e31d61ddd5d16769709aedbbe95138f42ef3e5280702c18f02.jpg',
    'bd6ee9c2a4d4d85a0d9439b3d14399e281c50118407ea5671015be48e6403066.jpg',
    'b66b9a25eea4577b1020598a67e9c623bbce4490455fd93113c8cac6b9726565.jpg',
    'e2244f2795aa2b06752ca08cc7bf01ff5d9211c43570a46c58df9d9d6c302e7e.jpg',
    '253d04c1011c78cc8fdbc1d5dc692004fb3cd411bd6fc23f99fcd1e0843870f9.jpg',
    'f14f189af1d9ad3449c9877cb554cc2bb15dfc6a3523baaa8f6a48605202c17a.jpg',
    ];

    public function __construct(Connection $db)
    {
        $this->db = $db;
        $this->faker = Factory::create();
    }

    public function create($count = 10)
    {
        $command = $this->db->createCommand();
        $tableProducts = '{{%products}}';
        $tableProductImages = '{{%product_images}}';
        $tableProductCharacteristics = '{{%product_characteristics}}';

        // Получаем всех производителей из БД
        $manufacturers = $this->db->createCommand('SELECT id FROM manufacturers')->queryColumn();
        if (empty($manufacturers)) {
            echo "No manufacturers found. Generate them first.\n";
            return;
        }

        // Получаем все характеристики и их возможные значения
        $characteristicsData = $this->db->createCommand("SELECT c.id AS characteristic_id, cv.value FROM characteristics c JOIN characteristic_values cv ON c.id = cv.characteristic_id")->queryAll();

        if (empty($characteristicsData)) {
            echo "No characteristics or characteristic values found. Generate them first.\n";
            return;
        }

        // Группируем характеристики по их ID
        $characteristics = [];
        foreach ($characteristicsData as $row) {
            $characteristics[$row['characteristic_id']][] = $row['value'];
        }

        for ($i = 0; $i < $count; $i++) {
            $name = $this->faker->company . " Smartwatch";
            $price = $this->faker->randomFloat(2, 15000, 50000);
            $description = $this->faker->sentence(10);
            $categoryId = 1; // Всегда 1
            $manufacturerId = $this->faker->randomElement($manufacturers);
            $sku = strtoupper($this->faker->bothify('SW-###'));
            $stockQuantity = $this->faker->numberBetween(10, 100);
            $discount = $this->faker->randomElement([0, 5, 10, 15, 20]);

            // Вставляем товар в таблицу products
            $command->insert($tableProducts, [
                'name' => $name,
                'price' => $price,
                'description' => $description,
                'category_id' => $categoryId,
                'manufacturer_id' => $manufacturerId,
                'SKU' => $sku,
                'stock_quantity' => $stockQuantity,
                'discount_percentage' => $discount,
                'created_at' => new \yii\db\Expression('NOW()'),
                'updated_at' => new \yii\db\Expression('NOW()'),
            ])->execute();

            $productId = $this->db->getLastInsertID();

            // Генерируем случайные картинки из массива
            for ($j = 0; $j < 2; $j++) {
                $randomImage = $this->images[array_rand($this->images)];
                $imagePath = '/uploads/products/' . $randomImage;

                $command->insert($tableProductImages, [
                    'product_id' => $productId,
                    'url' => $imagePath,
                    'is_main' => $j === 0 ? 1 : 0,
                    'sort_order' => $j,
                ])->execute();
            }

            // Присваиваем продукту все характеристики с их случайными значениями
            foreach ($characteristics as $charId => $values) {
                $randomValue = $this->faker->randomElement($values);
                $command->insert($tableProductCharacteristics, [
                    'product_id' => $productId,
                    'characteristic_id' => $charId,
                    'value' => $randomValue,
                ])->execute();
            }
        }

        echo "Inserted {$count} products with their images and characteristics.\n";
    }
}
