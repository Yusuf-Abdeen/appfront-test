@extends('layouts.app')

@section('title', 'Products')

@section('styles')
    <style>
        .price-container {
            display: flex;
            flex-direction: column;
            margin-bottom: 1rem;
        }

        .price-usd {
            font-size: 1.5rem;
            font-weight: bold;
            color: #e74c3c;
        }

        .price-eur {
            font-size: 1.2rem;
            color: #7f8c8d;
        }
    </style>
@endsection

@section('content')
    <h1>Products</h1>

    <div class="products-grid">
        @forelse ($products as $product)
            <div class="product-card">
                @if ($product->image)
                    <img src="{{ asset($product->image) }}" class="product-image" alt="{{ $product->name }}">
                @endif
                <div class="product-info">
                    <h2 class="product-title">{{ $product->name }}</h2>
                    <p class="product-description">{{ Str::limit($product->description, 100) }}</p>
                    <div class="price-container">
                        <span class="price-usd">{{ $product->formatted_price }}</span>
                        <span class="price-eur">{{ $product->formattedEurPrice($exchangeRate) }}</span>
                    </div>
                    <a href="{{ route('products.show', $product) }}" class="btn btn-primary">View Details</a>
                </div>
            </div>
        @empty
            <div class="empty-message">
                <p>No products found.</p>
            </div>
        @endforelse
    </div>

    <div style="margin-top: 20px; text-align: center; font-size: 0.9rem; color: #7f8c8d;">
        <p>Exchange Rate: 1 USD = {{ number_format($exchangeRate, 4) }} EUR</p>
    </div>
@endsection
