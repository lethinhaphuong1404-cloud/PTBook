<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function showMomoGateway($plan) 
    {
        $price = ($plan == 'monthly') ? 50000 : 500000;
        // Đảm bảo file này tồn tại: resources/views/payment/momo.blade.php
        return view('payment.momo', compact('price', 'plan')); 
    }

    public function processMomoFake(Request $request) 
    {
        $user = Auth::user(); // Dùng Auth facade hoặc auth() helper đều được
        $user->is_vip = true;

        if ($request->plan == 'monthly') {
            $user->vip_expires_at = now()->addDays(30);
        } elseif ($request->plan == 'yearly') {
            $user->vip_expires_at = now()->addDays(365);
        }

        $user->save();

        return redirect()->route('profile.index')->with('success', 'Bạn đã nâng cấp VIP thành công!');
    }
}