# vDevs - v2

### Yêu cầu hệ thống
- PHP 5.6 (Extensions: mbstring, mysql)
- MySQL 5.6
- Apache

### Hướng dẫn cài đặt

1. Upload các file lên server

2. Import file vdevs.sql vào DB

3. Trỏ 2 domain lần lượt vào 2 thư mục
- api: api.domain.com
- forum: domain.com hoặc forum.domain.com

2 domain trên có thể thay đổi nhưng phải chung miền. (trong ví dụ trên là domain.com)

4. Sửa config trong file forum/system/config.php

- DEV_MODE: true/false tương ứng với môi trường local/production
- SMTP_USER: gmail của tài khoản Google dùng send mail
- SMTP_PASSWORD: app password của tài khoản Google dùng send mail
- VERSION: thay đổi khi update file CSS/JS để tránh bị cache
- SITE_SCHEME/SITE_HOST/SITE_PATH: scheme/host/path của SITE_URL, SITE_PATH sử dụng khi không cài đặt vào thư mục gốc của server.
- API_URL: url đã trỏ vào thư mục API
- FB_APP_ID/FB_APP_SECRET: thông tin app Facebook dùng để đăng nhập bằng Facebook
- DB_HOST/DB_NAME/DB_USER/DB_PASS: thông tin Database
- SALT: chuỗi ký tự ngẫu nhiên dùng cho 1 số chức năng cần mã hóa
- IMGUR_CLIENT_ID: Imgur API client ID
- IMGUR_ALBUM_ID: // Imgur album id for upload
- IMGUR_ALBUM_DELETEHASH: // or Imgur album delete hash for upload (Anonymous album)
- IMAGE_PER_MESSAGE: giới hạn số ảnh mỗi message
- MAX_POLL_RESPONSE: giới hạn số câu trả lời cho bình chọn trong diễn đàn
- GA_ID: Google Analytics ID
- GSV_CODE: Google Site Verification code, dùng để verify khi add site vào Google
- BUY_COIN_RATIO: tỉ lệ mua coin từ Gold
- MIN_FORUM_MESSAGE_LENGTH: độ dài tối thiểu của bình luận trong diễn đàn

5. Done
