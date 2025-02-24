<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\ServerErrorHttpException;
use yii\filters\auth\HttpBearerAuth;
use yii\web\NotFoundHttpException;
use app\models\Products;
use app\models\ProductImages;
use yii\web\UploadedFile;
use app\models\ProductCharacteristics;
use yii\web\UnprocessableEntityHttpException;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

class ProductsController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        unset($behaviors['verbs']);

        // Authentication via Bearer Token
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];

        // Access control
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                
                [
                    'allow' => true,
                    'actions' => ['index', 'view'],
                    'roles' => ['@'], // Authorized users
                ],
                [
                    'allow' => true,
                    'actions' => ['create', 'update', 'delete'],
                    'matchCallback' => function ($rule, $action) {
                        return Yii::$app->user->identity->getRole() === 'admin'; // Role check
                    },
                ],
            ],
            'denyCallback' => function () {
                throw new ForbiddenHttpException('You do not have sufficient rights to perform this action.');
            },
        ];

        return $behaviors;
    }

    // Method to get all products
    public function actionIndex()
{
    // Получаем все товары
    $products = Products::find()->with('productImages')->all();

    // Новинки - последние  добавленные товара
    $newArrivals = Products::find()
        ->orderBy(['created_at' => SORT_DESC])
        ->limit(20)
        ->with('productImages')
        ->all();

    // Выгодные предложения - товары с самой большой скидкой
    $bestDeals = Products::find()
        ->where(['>', 'discount_percentage', 0])
        ->orderBy(['discount_percentage' => SORT_DESC]) // Сортируем по скидке
        ->limit(20) // Выбираем  товары с максимальной скидкой
        ->with('productImages')
        ->all();

    // Преобразуем данные для API
    $transformProduct = function ($product) {
        // Рассчитываем цену с учетом скидки
        $discountedPrice = $product->price;
        if ($product->discount_percentage) {
            // Считаем цену со скидкой
            $discountedPrice = $product->price * (1 - $product->discount_percentage / 100);
        }

        return [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'discounted_price' => round($discountedPrice, 2),  // Цена со скидкой
            'description' => $product->description,
            'discount_percentage' => $product->discount_percentage,  // Процент скидки
            'image' => isset($product->productImages[0]) 
                ? Yii::$app->request->hostInfo . Yii::$app->request->baseUrl . $product->productImages[0]->url
                : null,
        ];
    };

    // Преобразуем все товары, новинки и выгодные предложения
    $result = array_map($transformProduct, $products);
    $newArrivals = array_map($transformProduct, $newArrivals);
    $bestDeals = array_map($transformProduct, $bestDeals);

    // Возвращаем данные в формате JSON
    return $this->asJson([
        'products' => $result,         // Все товары
        'newArrivals' => $newArrivals,  // Новинки
        'bestDeals' => $bestDeals,      // Выгодные предложения
    ]);
}

    

    
    
    
    
    

    // Method to get one product, including images and characteristics
    public function actionView($id)
    {
        $product = Products::findOne($id);
        if (!$product) {
            throw new NotFoundHttpException("Product not found.");
        }

        // Load related images and characteristics
        $images = ProductImages::find()->where(['product_id' => $id])->all();
        $characteristics = ProductCharacteristics::find()->where(['product_id' => $id])->all();

        return [
            'product' => $product,
            'images' => $images,
            'characteristics' => $characteristics
        ];
    }

    // Method to create a product with images and characteristics
    public function actionCreate()
    {
        $model = new Products();

        if (Yii::$app->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction(); // Start transaction

            try {
                // Load data from the request
                $model->load(Yii::$app->getRequest()->getBodyParams(), '');

                // Validate the model
                if (!$model->validate()) {
                    Yii::info('Validation errors: ' . json_encode($model->errors), 'test-product');
                    throw new UnprocessableEntityHttpException(json_encode($model->errors));
                }

                // Log data before saving
                Yii::debug('Product data before save: ' . json_encode($model->attributes), 'test-product');

                // Save the product model
                if (!$model->save()) {
                    Yii::error('Failed to save product: ' . json_encode($model->errors), 'test-product');
                    throw new ServerErrorHttpException('Failed to create the product.');
                }

                $product_id = $model->id;

                // Handle image uploads
                Yii::debug('Uploaded file: ' . json_encode($_FILES), 'test-product');
                $files = UploadedFile::getInstancesByName('images');
                Yii::debug('Total files uploaded: ' . count($files), 'test-product');
                if ($files) {
                    $is_main_values = Yii::$app->request->post('is_main', []);
                    $sort_order_values = Yii::$app->request->post('sort_order', []);
                
                    foreach ($files as $index => $file) {
                        Yii::debug('Processing file: ' . $file->name, 'test-product');
                        // Validate file
                        if (!$this->validateImage($file)) {
                            Yii::warning('Invalid image file: ' . $file->name, 'test-product');
                            throw new UnprocessableEntityHttpException('Invalid image file.');
                        }
                
                        $filePath = $this->uploadFile($file);
                        if ($filePath) {
                            // Save each image to the ProductImages table
                            $image = new ProductImages();
                            $image->product_id = $product_id;
                            $image->url = $filePath;
                
                            // Get is_main and sort_order for the current image
                            $image->is_main = $is_main_values[$index] ?? 0; // Default to 0 if not provided
                            $image->sort_order = $sort_order_values[$index] ?? 0; // Default to 0 if not provided
                
                            if (!$image->save()) {
                                Yii::error('Failed to save product image: ' . json_encode($image->errors), 'test-product');
                                throw new ServerErrorHttpException('Failed to save product image.');
                            }
                        } else {
                            Yii::error('Failed to upload file during create', 'test-product');
                            throw new UnprocessableEntityHttpException('Failed to upload file.');
                        }
                    }
                } else {
                    Yii::error('No files uploaded', 'test-product');
                    throw new UnprocessableEntityHttpException('No files uploaded.');
                }

                // Handle characteristics
                $characteristicsData = Yii::$app->request->post('characteristics', []);
                Yii::debug('Characteristics ' . json_encode($characteristicsData), 'test-product');

                $characteristics = [];
                foreach ($characteristicsData as $charData) {
                    $characteristics[] = [
                        'product_id' => $product_id,
                        'characteristic_id' => $charData['characteristic_id'],
                        'value' => $charData['value'],
                    ];
                }

                if (!empty($characteristics)) {
                    // Use bulk insert for characteristics
                    if (!Yii::$app->db->createCommand()->batchInsert(ProductCharacteristics::tableName(),
                        ['product_id', 'characteristic_id', 'value'], $characteristics)->execute()) {
                            Yii::error('Failed to batch insert product characteristics.', 'test-product');
                            throw new ServerErrorHttpException('Failed to save product characteristics.');
                    }
                }

                $transaction->commit(); // Commit transaction
                return $model;

            } catch (\Exception $e) {
                $transaction->rollBack(); // Rollback transaction on error
                Yii::error('Error creating product: ' . $e->getMessage() . ' ' . $e->getTraceAsString(), 'test-product');
                throw $e; // Re-throw the exception to be handled by the error handler
            }
        }

        throw new \yii\web\MethodNotAllowedHttpException('Method Not Allowed');
    }

    /**
     * Image validation
     *
     * @param UploadedFile $file
     * @return bool
     */
    private function validateImage(UploadedFile $file): bool
    {
        Yii::debug('Validating image: ' . $file->name, 'test-product'); // Log image validation

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp']; // Allowed MIME types
        $maxFileSize = 2 * 1024 * 1024; // Max file size: 2MB

        if ($file->size > $maxFileSize) {
            Yii::warning('File size exceeds limit: ' . $file->name, 'test-product');
            return false;
        }

        // Get MIME type based on file content
        $mimeType = mime_content_type($file->tempName);
        Yii::debug('MIME type from content: ' . $mimeType, 'test-product');

        if (!in_array($mimeType, $allowedMimeTypes)) {
            Yii::warning('Invalid file type: ' . $file->name, 'test-product');
            return false;
        }

        Yii::info('Image validated successfully: ' . $file->name, 'test-product');
        return true;
    }
    
    // Method to upload files
    private function uploadFile($file)
    {
        $directory = Yii::getAlias('@webroot/uploads/products/');
        if (!file_exists($directory)) {
            Yii::debug('Directory does not exist. Creating: ' . $directory, 'test-product');
            mkdir($directory, 0777, true);  // Create folder if it does not exist
        }

        $fileName = uniqid() . '.' . $file->extension;
        $filePath = $directory . $fileName;

        // Log file name and path
        Yii::debug('File path: ' . $filePath, 'test-product');

        if ($file->saveAs($filePath)) {
            Yii::info('File uploaded successfully: ' . $filePath, 'test-product');
            return '/uploads/products/' . $fileName;
        } else {
            Yii::error('Failed to save file: ' . $file->error, 'test-product');
            return false;
        }
    }

 // Метод для обновления продукта и загрузки изображений
 public function actionUpdate($id)
{
    $product = Products::findOne($id);
    if (!$product) {
        throw new NotFoundHttpException("Product not found.");
    }

    if ($product->load(Yii::$app->request->post(), '') && $product->save()) {
        Yii::info("Продукт с ID $id успешно обновлен.", 'test-product');
        return $product;
    }

    throw new ServerErrorHttpException('Не удалось обновить продукт.');
}
 /**
  * Метод для удаления старого изображения.
  *
  * @param string $imageUrl
  */
 protected function deleteImageFile($imageUrl)
 {
    // Преобразуем URL в путь к файлу (предполагается, что изображения хранятся в папке 'uploads/products')
    $imagePath = Yii::getAlias('@webroot') . $imageUrl;

    // Проверяем, существует ли файл
    if (file_exists($imagePath)) {
        if (unlink($imagePath)) {
            Yii::info("Изображение $imagePath успешно удалено с сервера.", 'test-product');
        } else {
            Yii::warning("Не удалось удалить изображение $imagePath.", 'test-product');
        }
    } else {
        Yii::warning("Изображение не найдено для удаления: $imagePath", 'test-product');
    }
}

    // Method to delete product and its dependencies
    public function actionDelete($id)
    {
        $product = Products::findOne($id);
        if (!$product) {
            throw new NotFoundHttpException("Product not found.");
        }
    
        // Удаляем все изображения и характеристики, привязанные к продукту
        foreach ($product->productImages as $image) {
            $this->deleteImageFile($image->url); // Удаляем файл изображения с сервера
        }
        ProductImages::deleteAll(['product_id' => $id]); // Удаляем записи изображений из базы данных
        ProductCharacteristics::deleteAll(['product_id' => $id]); // Удаляем характеристики из базы данных
    
        // Удаляем сам продукт
        if ($product->delete() === false) {
            throw new ServerErrorHttpException("Failed to delete the product.");
        }
    
        return 'Product deleted successfully';
    }
}
