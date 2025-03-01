<?php

namespace app\factories;

use yii\db\Connection;

class CharacteristicsFactory
{
    protected $db;
    protected $characteristics = [
        ['name' => 'Дисплей', 'description' => 'Тип и характеристики дисплея', 'values' => ['AMOLED', 'LCD', 'E-Ink', 'OLED']],
        ['name' => 'Процессор', 'description' => 'Модель и мощность процессора', 'values' => ['Apple S9', 'Snapdragon W5', 'Exynos 9110']],
        ['name' => 'Объем памяти', 'description' => 'Объем оперативной и встроенной памяти', 'values' => ['1GB/16GB', '2GB/32GB', '4GB/64GB']],
        ['name' => 'Операционная система', 'description' => 'ОС устройства', 'values' => ['watchOS', 'Wear OS', 'HarmonyOS', 'RTOS']],
        ['name' => 'Автономность', 'description' => 'Время работы от одного заряда', 'values' => ['1 день', '2 дня', 'до 1 недели']],
        ['name' => 'Материал корпуса', 'description' => 'Материал корпуса', 'values' => ['Алюминий', 'Нержавеющая сталь', 'Пластик']],
        ['name' => 'Влагозащита', 'description' => 'Уровень защиты от воды и пыли', 'values' => ['IP67', 'IP68', '5 ATM']],
        ['name' => 'Поддержка SIM-карты', 'description' => 'Возможность установки SIM-карты', 'values' => ['Нет', 'eSIM']],
        ['name' => 'Подключение', 'description' => 'Способы связи', 'values' => ['Bluetooth 5.0', 'Wi-Fi', 'NFC']],
        ['name' => 'Функции', 'description' => 'Основные функции', 'values' => ['Шагомер', 'Пульсометр', 'ЭКГ', 'Анализ сна']],
        ['name' => 'Цвет', 'description' => 'Цвет корпуса и ремешка', 'values' => ['Черный', 'Белый', 'Серебристый', 'Золотой', 'Синий']]
    ];

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function create()
    {
        $command = $this->db->createCommand();
        $tableCharacteristics = '{{%characteristics}}';
        $tableValues = '{{%characteristic_values}}';

        foreach ($this->characteristics as $char) {
            // Вставляем характеристику в characteristics
            $command->insert($tableCharacteristics, [
                'name' => $char['name'],
                'description' => $char['description'],
            ])->execute();

            // Получаем ID только что добавленной характеристики
            $charId = $this->db->getLastInsertID();

            // Добавляем возможные значения в characteristic_values
            foreach ($char['values'] as $value) {
                $command->insert($tableValues, [
                    'characteristic_id' => $charId,
                    'value' => $value,
                ])->execute();
            }
        }

        echo "Inserted " . count($this->characteristics) . " characteristics and their values.\n";
    }
}
