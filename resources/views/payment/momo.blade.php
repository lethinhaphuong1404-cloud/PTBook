<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán MoMo - PTBook</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#0a0a0a] flex items-center justify-center min-h-screen p-6">

<div class="max-w-md w-full bg-white text-black rounded-[2.5rem] overflow-hidden shadow-2xl">
    <div class="bg-[#ae2070] p-8 text-center text-white">
        <h2 class="text-2xl font-extrabold tracking-tight">Thanh toán MoMo</h2>
        <p class="text-sm opacity-80 mt-1">Nâng cấp VIP tự động 24/7</p>
    </div>

    <div class="p-8 text-center">
        <div class="bg-gray-50 p-4 rounded-3xl inline-block mb-8 border-2 border-gray-100 shadow-inner">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=MOMO_PAYMENT_FOR_USER_{{ auth()->id() }}" 
                 alt="Momo QR" class="rounded-xl">
        </div>

        <div class="space-y-4 text-left bg-gray-50 p-5 rounded-2xl border border-gray-200">
            <div class="flex justify-between items-center">
                <span class="text-gray-500 text-sm">Số tiền cần trả:</span>
                <span class="font-black text-xl text-[#ae2070]">{{ number_format($price) }}đ</span>
            </div>
            <div class="h-px bg-gray-200 w-full"></div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Gói dịch vụ:</span>
                <span class="font-bold uppercase text-gray-800">{{ $plan == 'monthly' ? 'Gói Tháng' : 'Gói Năm' }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Nội dung chuyển khoản:</span>
                <span class="font-bold text-blue-600">VIP PTBOOK {{ auth()->id() }}</span>
            </div>
        </div>

        <form action="{{ route('payment.momo.confirm') }}" method="POST" class="mt-8">
            @csrf
            <input type="hidden" name="plan" value="{{ $plan }}">
            <button type="submit" class="w-full bg-[#ae2070] text-white py-4 rounded-2xl font-bold text-sm uppercase tracking-widest hover:bg-[#8e1a5c] transition-all shadow-xl shadow-pink-900/20 active:scale-95">
                XÁC NHẬN ĐÃ CHUYỂN TIỀN
            </button>
        </form>
        
        <a href="{{ route('profile.index') }}" class="block mt-6 text-xs text-gray-400 hover:text-[#ae2070] transition-colors font-medium">
            ← Quay lại hồ sơ cá nhân
        </a>
    </div>
</div>

</body>
</html>