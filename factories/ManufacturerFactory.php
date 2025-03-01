<?php

namespace app\factories;

use Faker\Factory;
use yii\db\Connection;

class ManufacturerFactory
{
    protected $faker;
    protected $db;
    protected $logoPaths = [
        '/uploads/manufacturers/7ff315f7c3f7cdf5f53dc5fed54aed1685a10900b0755657eb1d1de6e82aed97.png',
        '/uploads/manufacturers/8d82bc5caa24319df5e0acdc4577dfb192e538305df51f050e25ad72a86c239e.png',
        '/uploads/manufacturers/9da1092afbcc0df7a313147ffba9b8ee3d38fd67676c716fee957e1389abc999.png',
        '/uploads/manufacturers/19e38872a86d945c8776964334bf660a9ccef9c936aca5ed609f4ecb684cae4c.png',
        '/uploads/manufacturers/48c56c3c5bd9830811a23d5b515786e463d553bbcffb185b2ab7d0de559b1a4f.png',
        '/uploads/manufacturers/69e02179171d693c6982c3cddf1dd5d03b35e60c46ea8173923960042d9defe6.png',
        '/uploads/manufacturers/98d9b5d02fcec9647f5117fa414768cb0cffdd9070eacc5b885fd39f950ceb86.png',
        '/uploads/manufacturers/128ec126a0f3ae83479f7d77b27acddd71b3de7888c5d69af799a75d19819447.png',
        '/uploads/manufacturers/611cd9f48cf251af70de71548bf1b79f83a1eef613d78066a157914f0c3b19ac.png',
        '/uploads/manufacturers/640b1f4c1ba0c376ab780b57f15165097be24077bbbc4d8cba5c941c06552e73.png',
        '/uploads/manufacturers/803a26bccbf00125dc8bf1c88135c3bb88da4f79225db7c37b21ac33dcc68aca.png',
        '/uploads/manufacturers/763c04b78628f3c4268b6d226590cd01418bdbd1912a2f190b4fd7fe1b7d95e6.png',
        '/uploads/manufacturers/1515d085d8bb855385bba0d4975a1031c637f159d94d2473e1f12ba39babe8aa.png',
        '/uploads/manufacturers/20194aefa7610ba75f4615fa5982fab7e285c192a599a8d22807cfa3d8b8fc7d.png',
        '/uploads/manufacturers/664957dcc564b0a1b3ee544e7a75059d36e89408e126663aeead7c665a483142.png',
        '/uploads/manufacturers/64427207a55ed51f1e72262a5863178ceed09b33c91947df428ffc99dc21ae08.png',
        '/uploads/manufacturers/c1cd823412e70a9dd8c2e80bde3b3f0b85fc67eae815aa042e6f442b551c49c3.png',
        '/uploads/manufacturers/d44d39e1c51a05a14496dedd187ed4fe51f72c57e56663598e25f54c3612c398.png',
        '/uploads/manufacturers/e6a798462b8345db6a0d535a7377fdd03a2a2c406f0051bfd613669866e29003.png',
        '/uploads/manufacturers/f2ec9729234a9f85010ececb213314d0fb13457fddf90f571bb71861be4b86d1.png',
        '/uploads/manufacturers/fc3f7cb46216c482681203096a4a685935e8010fa761883c8134bd1a8a857ce6.png',
        '/uploads/manufacturers/f029627be01a67c5bf90e39587c84978426d8e73169f280c69077daf3f92f5b5.png',
    ];

    public function __construct(Connection $db)
    {
        $this->db = $db;
        $this->faker = Factory::create();
    }

    public function create(int $count = 10)
    {
        $command = $this->db->createCommand();
        $table = '{{%manufacturers}}';

        for ($i = 0; $i < $count; $i++) {
            $command->insert($table, [
                'name' => $this->faker->company,
                'country' => $this->faker->country,
                'logo' => $this->logoPaths[array_rand($this->logoPaths)], // случайный логотип
                'website' => $this->faker->url,
                'contact_email' => $this->faker->companyEmail,
                'phone' => $this->faker->phoneNumber,
                'description' => $this->faker->paragraph,
            ])->execute();
        }

        echo "Inserted $count manufacturers into $table\n";
    }
}
