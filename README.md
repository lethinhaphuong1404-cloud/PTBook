## 📚 PTApp - Nền tảng Đọc Truyện & Nghe Sách Nói Trực Tuyến
PTApp là một hệ thống giải trí đa phương tiện hiện đại, kết hợp giữa trải nghiệm đọc truyện chữ và nghe sách nói theo phong cách Netflix Premium. Dự án được tối ưu hóa cho trải nghiệm người dùng với giao diện tối (Dark Mode), hỗ trợ đa ngôn ngữ và khả năng truyền tải dữ liệu mượt mà.

## 🚀 Công nghệ sử dụng (Tech Stack)
Hệ thống được xây dựng trên mô hình Hybrid hiện đại nhằm tối ưu hiệu suất và khả năng mở rộng:

Frontend: * Laravel Blade: Xử lý render giao diện từ phía Server (SSR) giúp tối ưu SEO.

 - Tailwind CSS v4: Framework CSS mới nhất cho giao diện mượt mà, phản hồi nhanh.

 - React JS: Xử lý các thành phần tương tác cao (như Trình phát Audio, Tìm kiếm thời gian thực).

Backend: * Laravel 11: Framework chính quản lý Logic, Database, và Xác thực (Auth).

 - Node.js: Microservice chuyên biệt để xử lý luồng (Streaming) Audio ổn định.

Database & Storage: * MySQL (XAMPP): Lưu trữ dữ liệu người dùng, thông tin truyện và lịch sử.

 - Git: Quản lý mã nguồn và lịch sử phát triển.

## ✨ Các tính năng nổi bật
1. Giao diện & Trải nghiệm (UI/UX)
   - Netflix-style UI: Giao diện Dark Mode sang trọng, tập trung vào hình ảnh nội dung.
  
   - Song ngữ (VI | EN): Chuyển đổi ngôn ngữ tức thì trên toàn bộ hệ thống.
  
   - Responsive Design: Trải nghiệm hoàn hảo trên mọi thiết bị (Mobile, Tablet, Desktop).

2. Quản lý nội dung (Streaming & Reader)
   - Smart Reader: Tùy chỉnh chế độ đọc (font chữ, cỡ chữ, khoảng cách dòng).
  
   - Audio Streaming: Nghe sách nói với trình phát thông minh, hỗ trợ chạy nền và tua nhanh/chậm.
  
   - Recommendation System: Gợi ý nội dung thông minh dựa trên hành vi người dùng.

3. Bảo mật & Tài khoản
   - Xác thực 2 lớp: Đăng ký/Đăng nhập bảo mật cao.
  
   - Gmail Reset Password: Hệ thống gửi mã token khôi phục mật khẩu qua SMTP Gmail (App Password).
  
   - Hàm băm bảo mật: Lưu trữ mật khẩu an toàn (Bcrypt/Argon2 thay cho MD5 lỗi thời).

## 🛠 Hướng dẫn cài đặt (Installation)
Yêu cầu hệ thống:
 - PHP >= 8.2

 - Node.js >= 18.x

 - Composer & NPM

 - XAMPP (đã bật Apache & MySQL)

Các bước thực hiện:
1. Clone dự án:

      git clone https://github.com/username/ptapp.git
     
      cd ptapp

3. Cài đặt các gói phụ thuộc:

      composer install
     
      npm install

4. Cấu hình môi trường (.env):

   - Sao chép file mẫu: cp .env.example .env
  
   - Cấu hình Database:
  
        DB_DATABASE=ptapp_db
       
        DB_USERNAME=root
       
        DB_PASSWORD=
     
   - Cấu hình Gmail SMTP (để gửi mail reset pass):
      
        MAIL_MAILER=smtp
       
        MAIL_HOST=smtp.gmail.com
       
        MAIL_PORT=587
       
        MAIL_USERNAME=your-email@gmail.com
       
        MAIL_PASSWORD=your-app-password

5. Khởi tạo Database & App Key:

    php artisan key:generate
   
    php artisan migrate

6. Chạy dự án:
  
   - Mở Terminal 1 (Vite): npm run dev
  
   - Mở Terminal 2 (Local Server): php artisan serve

## 📂 Cấu trúc thư mục quan trọng
 - app/Http/Controllers: Chứa các Logic xử lý nghiệp vụ (Auth, Profile, Story).

 - resources/views: Chứa các giao diện Blade (Home, Detail, Reader).

 - resources/css/app.css: Nơi cấu hình Tailwind CSS v4.

 - routes/web.php: Định nghĩa các luồng URL của hệ thống.
