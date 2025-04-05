<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product; // Added missing import
use App\Repositories\ProductRepository;
use App\Jobs\SendPriceChangeNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class UpdateProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:update {id} {--name=} {--description=} {--price=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update a product with the specified details';

    /**
     * @var ProductRepository
     */
    protected ProductRepository $productRepository;

    /**
     * Create a new command instance.
     *
     * @param ProductRepository $productRepository
     * @return void
     */
    public function __construct(ProductRepository $productRepository)
    {
        parent::__construct();
        $this->productRepository = $productRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $id = $this->argument('id');
        $product = $this->productRepository->findById($id);

        if (!$product) {
            $this->error("Product with ID {$id} not found.");
            return 1;
        }

        $data = $this->collectProductData();

        if (empty($data)) {
            $this->info("No changes provided. Product remains unchanged.");
            return 0;
        }

        $validator = Validator::make($data, $this->validationRules());

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        $oldPrice = $product->price;

        $this->productRepository->update($product, $data);
        $this->info("Product updated successfully.");

        $this->handlePriceChange($product, $oldPrice);

        return 0;
    }

    /**
     * Collect product data from command options
     *
     * @return array
     */
    private function collectProductData(): array
    {
        $data = [];

        if ($this->option('name')) {
            $data['name'] = $this->option('name');
        }

        if ($this->option('description')) {
            $data['description'] = $this->option('description');
        }

        if ($this->option('price')) {
            $data['price'] = $this->option('price');
        }

        return $data;
    }

    /**
     * Get validation rules for product data
     *
     * @return array
     */
    private function validationRules(): array
    {
        return [
            'name' => 'sometimes|required|string|min:3',
            'description' => 'sometimes|nullable|string',
            'price' => 'sometimes|numeric|min:0',
        ];
    }

    /**
     * Handle price change notification if needed
     *
     * @param Product $product
     * @param float $oldPrice
     * @return void
     */
    private function handlePriceChange(Product $product, float $oldPrice): void
    {
        if ($oldPrice != $product->price) {
            $this->info("Price changed from {$oldPrice} to {$product->price}.");

            $notificationEmail = config('app.price_notification_email', 'admin@example.com');

            try {
                SendPriceChangeNotification::dispatch(
                    $product,
                    $oldPrice,
                    $product->price,
                    $notificationEmail
                );
                $this->info("Price change notification dispatched to {$notificationEmail}.");
            } catch (\Exception $e) {
                Log::error('Failed to dispatch price change notification', [
                    'message' => $e->getMessage(),
                    'product_id' => $product->id
                ]);
                $this->error("Failed to dispatch price change notification: " . $e->getMessage());
            }
        }
    }
}
