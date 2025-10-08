# 🛒 Website Thương mại Điện tử "DailyMart"

![WordPress](https://img.shields.io/badge/WordPress-6.6.1-blue.svg?style=for-the-badge&logo=wordpress)
![WooCommerce](https://img.shields.io/badge/WooCommerce-9.2.0-purple.svg?style=for-the-badge&logo=woocommerce)
![Project Status](https://img.shields.io/badge/status-in%20progress-yellow.svg?style=for-the-badge)
![License](https://img.shields.io/badge/license-MIT-green.svg?style=for-the-badge)

> **Mô tả ngắn:** Đây là đồ án xây dựng một website thương mại điện tử hoàn chỉnh, mô phỏng theo các sàn TMĐT hiện đại, sử dụng nền tảng WordPress và WooCommerce.

---

## 🚀 1. Tổng quan Đồ án

* **Tên đồ án:** Xây dựng Website Thương mại Điện tử "DailyMart".
* **Giảng viên hướng dẫn:** Phan Thanh Nhuần
* **Nhóm sinh viên thực hiện:**
    * Lý Minh Phước
    * Lê Xuân Vũ
    * Nguyễn Hoàng Phi
    * Nguyễn Văn Thuật
    * Đặng Ngọc Hạnh Nguyên

### 🎯 Mục tiêu chính
* Áp dụng kiến thức về WordPress & WooCommerce để xây dựng một website TMĐT hoàn chỉnh.
* Mô phỏng thành công quy trình hoạt động của một trang TMĐT thực tế.
* Tích hợp các tính năng thanh toán và vận chuyển phổ biến tại Việt Nam (chế độ Sandbox/Test).
* Xây dựng website có giao diện thân thiện, chuyên nghiệp, và tương thích trên nhiều thiết bị (responsive).

### 📋 Phạm vi đồ án
* ✅ **Bao gồm:** Xây dựng chức năng front-end & back-end, triển khai demo trên hosting/localhost, sử dụng dữ liệu mẫu.
* ❌ **Không bao gồm:** Các hoạt động marketing, SEO off-page, quản lý chuỗi cung ứng thực tế, phát triển ứng dụng di động.

---

## 🛠️ 2. Phân tích và Thiết kế Hệ thống

### 2.1. Công nghệ sử dụng (Tech Stack)
* **⚙️ Nền tảng chính:** WordPress (Phiên bản mới nhất).
* **🛒 Plugin TMĐT:** WooCommerce.
* **🎨 Giao diện (Theme):** Astra (Miễn phí).
    > *Lý do lựa chọn:* Tốc độ nhanh, tùy biến cao, tích hợp sâu với WooCommerce, phù hợp để xây dựng cửa hàng chuyên nghiệp mà không tốn chi phí.
* **🧩 Các Plugin Hỗ trợ chính:**
    * **Page Builder:** Elementor
    * **Thanh toán:** WooCommerce MoMo Gateway, WooCommerce VNPay Gateway
    * **SEO:** Rank Math SEO
    * **Caching:** LiteSpeed Cache / WP Super Cache

### 2.2. Kiến trúc Chức năng
<details>
<summary><b>🧑‍💻 Chức năng dành cho Khách hàng (Click để xem chi tiết)</b></summary>

1.  **🏠 Trang chủ:** Hiển thị sản phẩm nổi bật, sản phẩm mới, danh mục, banner khuyến mãi.
2.  **🛍️ Trang Cửa hàng:** Liệt kê sản phẩm với bộ lọc thông minh (giá, danh mục, thuộc tính).
3.  **📦 Trang Chi tiết sản phẩm:** Hiển thị đầy đủ thông tin, hình ảnh, mô tả, giá, đánh giá.
4.  **🔍 Tìm kiếm sản phẩm:** Tìm kiếm sản phẩm nhanh chóng theo tên.
5.  **🛒 Giỏ hàng:** Thêm/xóa/cập nhật số lượng sản phẩm.
6.  **💳 Thanh toán:**
    * Điền thông tin giao hàng.
    * Lựa chọn phương thức vận chuyển.
    * Lựa chọn phương thức thanh toán (COD, Chuyển khoản, MoMo/VNPay test).
7.  **👤 Quản lý Tài khoản:**
    * Đăng ký / Đăng nhập.
    * Xem lịch sử đơn hàng.
    * Quản lý địa chỉ giao hàng.
</details>

<details>
<summary><b>👑 Chức năng dành cho Quản trị viên (Click để xem chi tiết)</b></summary>

1.  **📊 Bảng điều khiển (Dashboard):** Tổng quan nhanh về doanh thu, đơn hàng, tình trạng cửa hàng.
2.  **🏷️ Quản lý Sản phẩm:** Thêm/sửa/xóa sản phẩm, danh mục, thuộc tính.
3.  **🧾 Quản lý Đơn hàng:** Xem danh sách, cập nhật trạng thái, in hóa đơn.
4.  **👥 Quản lý Khách hàng:** Xem danh sách khách hàng và lịch sử mua hàng.
5.  **🎉 Quản lý Mã giảm giá:** Tạo và quản lý các chương trình coupon, khuyến mãi.
6.  **📈 Xem Báo cáo:** Báo cáo doanh thu, sản phẩm bán chạy.
7.  **⚙️ Cài đặt Cửa hàng:** Cấu hình thông tin, tiền tệ, thuế, thanh toán, vận chuyển.
</details>

---

## 🗓️ 3. Kế hoạch Thực hiện

| Giai đoạn | Nội dung công việc | Thời gian dự kiến |
| :--- | :--- | :--- |
| **1️⃣ Giai đoạn 1** | **Chuẩn bị và Cài đặt** <br> - Đăng ký hosting/cài đặt localhost. <br> - Cài đặt WordPress, Theme & Plugins. | Tuần 1 |
| **2️⃣ Giai đoạn 2** | **Cấu hình hệ thống** <br> - Cấu hình cửa hàng, tiền tệ. <br> - Cấu hình thanh toán & vận chuyển. | Tuần 2 |
| **3️⃣ Giai đoạn 3** | **Xây dựng & Nhập liệu** <br> - Thiết kế giao diện bằng Elementor. <br> - Nhập liệu mẫu cho 20-30 sản phẩm. | Tuần 3 - 4 |
| **4️⃣ Giai đoạn 4** | **Kiểm thử và Tối ưu** <br> - Thực hiện đặt hàng thử trên toàn bộ luồng. <br> - Kiểm tra responsive & tối ưu tốc độ. | Tuần 5 |
| **5️⃣ Giai đoạn 5**| **Hoàn thiện & Báo cáo** <br> - Viết báo cáo đồ án, chụp ảnh màn hình. <br> - Chuẩn bị bảo vệ đồ án. | Tuần 6 |

---

## 🧪 4. Dữ liệu Demo

* **Sản phẩm mẫu:**
    * **Nguồn:** Thông tin & hình ảnh minh họa từ Shopee, Tiki.
    * **Danh mục:** Đồ điện tử, Thời trang, Đồ gia dụng.
* **Tài khoản mẫu để kiểm thử:**
    ```
    # Tài khoản Quản trị viên
    Username: admin
    Password: [your_password]

    # Tài khoản Khách hàng
    Username: khachhang
    Password: [your_password]
    ```

---

## ✨ 5. Kết quả và Hướng phát triển

* **🎯 Kết quả dự kiến:** Hoàn thành một website TMĐT hoạt động ổn định, đáp ứng đầy đủ chức năng, giao diện trực quan, dễ sử dụng.
* **🌱 Hướng phát triển trong tương lai:**
    * Tích hợp chatbot hỗ trợ khách hàng.
    * Xây dựng hệ thống khách hàng thân thiết, tích điểm.
    * Phát triển tính năng so sánh sản phẩm.
    * Tối ưu SEO nâng cao.