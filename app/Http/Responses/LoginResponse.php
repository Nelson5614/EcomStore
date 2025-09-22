<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        // Check if we have a specific intended URL from checkout
        if ($request->session()->has('intended_url')) {
            $intendedUrl = $request->session()->get('intended_url');
            $request->session()->forget('intended_url');
            return redirect($intendedUrl);
        }
        
        // Check if user was trying to checkout via Laravel's intended URL
        if ($request->session()->has('url.intended')) {
            $intendedUrl = $request->session()->get('url.intended');
            
            // If intended URL is checkout, redirect to checkout
            if (str_contains($intendedUrl, 'checkout')) {
                return redirect()->route('checkout.index');
            }
            
            // Otherwise redirect to intended URL
            return redirect()->intended();
        }

        // Check if user has items in cart and redirect to checkout
        if (auth()->check()) {
            $cartData = \App\Helpers\Cart::getProductsAndCartItems();
            $cartItemsData = $cartData[1];
            
            if (count($cartItemsData) > 0) {
                return redirect()->route('checkout.index');
            }
        }

        // Default redirect to home
        return redirect('/');
    }
}
