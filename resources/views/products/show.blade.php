@extends('layouts.app')

@section('title', $product->name)

@section('styles')
    <style>
        .price-container {
            display: flex;
            flex-direction: column;
            margin-bottom: 1rem;
        }

        .price-usd {
            font-size: 2rem;
            font-weight: bold;
            color: #e74c3c;
        }

        .price-eur {
            font-size: 1.5rem;
            color: #7f8c8d;
        }
    </style>
@endsection

@section('content')
    <div class="product-detail">
        <div>
            @if ($product->image)
                <img src="{{ asset($product->image) }}" class="product-detail-image" alt="{{ $product->name }}">
            @endif
        </div>
        <div class="product-detail-info">
            <h1 class="product-detail-title">{{ $product->name }}</h1>
            <p class="product-id">Product ID: {{ $product->id }}</p>

            <div class="price-container">
                <span class="price-usd">{{ $product->formatted_price }}</span>
                <span class="price-eur">{{ $product->formattedEurPrice($exchangeRate) }}</span>
            </div>

            <div class="divider"></div>

            <div class="product-detail-description">
                <h4 class="description-title">Description</h4>
                <p>{{ $product->description }}</p>
            </div>

            <div class="action-buttons">
                <a href="{{ route('home') }}" class="btn btn-secondary">Back to Products</a>
                <button class="btn btn-primary">Add to Cart</button>
            </div>

            <p style="margin-top: 20px; font-size: 0.9rem; color: #7f8c8d;">
                Exchange Rate: 1 USD = {{ number_format($exchangeRate, 4) }} EUR
            </p>
        </div>
    </div>
@endsection
