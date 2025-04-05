<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendPriceChangeNotification;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    protected ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Get all products
     *
     * @return Collection
     */
    public function getAllProducts(): Collection
    {
        return $this->productRepository->getAll();
    }

    /**
     * Create a new product
     *
     * @param array $data
     * @return Product
     */
    public function createProduct(array $data): Product
    {
        return $this->productRepository->create($data);
    }

    /**
     * Update a product
     *
     * @param Product $product
     * @param array $data
     * @return Product
     */
    public function updateProduct(Product $product, array $data): Product
    {
        $oldPrice = $product->price;

        $updatedProduct = $this->productRepository->update($product, $data);

        $this->handlePriceChange($updatedProduct, $oldPrice);

        return $updatedProduct;
    }

    /**
     * Update product image
     *
     * @param Product $product
     * @param string $imagePath
     * @return Product
     */
    public function updateProductImage(Product $product, string $imagePath): Product
    {
        return $this->productRepository->update($product, ['image' => $imagePath]);
    }

    /**
     * Delete a product
     *
     * @param Product $product
     * @return bool
     */
    public function deleteProduct(Product $product): bool
    {
        return $this->productRepository->delete($product);
    }

    /**
     * Handle price change notification if needed
     *
     * @param Product $product
     * @param float $oldPrice
     * @return void
     */
    protected function handlePriceChange(Product $product, float $oldPrice): void
    {
        if ($oldPrice != $product->price) {
            $notificationEmail = config('app.price_notification_email', 'admin@example.com');

            try {
                SendPriceChangeNotification::dispatch(
                    $product,
                    $oldPrice,
                    $product->price,
                    $notificationEmail
                );

                Log::info('Price change notification dispatched', [
                    'product_id' => $product->id,
                    'old_price' => $oldPrice,
                    'new_price' => $product->price,
                    'notification_email' => $notificationEmail
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to dispatch price change notification', [
                    'message' => $e->getMessage(),
                    'product_id' => $product->id
                ]);
            }
        }
    }
}