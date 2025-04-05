<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ExchangeRateService;
use App\Repositories\ProductRepository;
use Illuminate\View\View;

class ProductController extends Controller
{
    protected ProductRepository $productRepository;
    protected ExchangeRateService $exchangeRateService;

    public function __construct(
        ProductRepository $productRepository,
        ExchangeRateService $exchangeRateService
    ) {
        $this->productRepository = $productRepository;
        $this->exchangeRateService = $exchangeRateService;
    }

    /**
     * Display a listing of products.
     *
     * @return View
     */
    public function index(): View
    {
        $products = $this->productRepository->getAll();
        $exchangeRate = $this->exchangeRateService->getUsdToEurRate();

        return view('products.list', compact('products', 'exchangeRate'));
    }

    /**
     * Display the specified product.
     *
     * @param Product $product
     * @return View
     */
    public function show(Product $product): View
    {
        $exchangeRate = $this->exchangeRateService->getUsdToEurRate();

        return view('products.show', compact('product', 'exchangeRate'));
    }
}
