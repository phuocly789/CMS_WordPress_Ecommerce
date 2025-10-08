# Báo cáo Đồ án: Xây dựng Website Thương mại Điện tử "DailyMart"
### Nền tảng: WordPress và WooCommerce

---

### 1. TỔNG QUAN ĐỒ ÁN

* **Tên đồ án:** Xây dựng Website Thương mại Điện tử "DailyMart" bằng nền tảng WordPress và WooCommerce.
* **Sinh viên thực hiện:** Lý Minh Phước, Lê Xuân Vũ, Nguyễn Hoàng Phi, Nguyễn Văn Thuật, Đặng Ngọc Hạnh Nguyên
* **Giảng viên hướng dẫn:** Phan Thanh Nhuần
* **Mục tiêu chính:**
    * Áp dụng kiến thức về nền tảng WordPress và plugin WooCommerce để xây dựng một website thương mại điện tử hoàn chỉnh.
    * Mô phỏng thành công quy trình hoạt động của một trang TMĐT thực tế: từ việc hiển thị sản phẩm, quản lý giỏ hàng, đặt hàng, thanh toán đến xử lý đơn hàng.
    * Tích hợp các tính năng thanh toán và vận chuyển phổ biến tại Việt Nam vào website (sử dụng chế độ Sandbox/Test).
    * Xây dựng một website có giao diện thân thiện, chuyên nghiệp, tương thích trên nhiều thiết bị (responsive) dù sử dụng theme miễn phí.
* **Phạm vi đồ án:**
    * **Bao gồm:** Xây dựng các chức năng front-end (cho người dùng) và back-end (cho quản trị viên). Triển khai website trên hosting/localhost để demo. Toàn bộ dữ liệu (sản phẩm, đơn hàng, người dùng) là dữ liệu mẫu phục vụ cho mục đích học tập.
    * **Không bao gồm:** Các hoạt động marketing, SEO off-page, quản lý chuỗi cung ứng thực tế, phát triển ứng dụng di động riêng.

### 2. PHÂN TÍCH VÀ THIẾT KẾ HỆ THỐNG

#### 2.1. Công nghệ sử dụng
* **Nền tảng chính:** WordPress (Phiên bản mới nhất).
* **Plugin Thương mại Điện tử:** WooCommerce.
* **Giao diện (Theme):** Astra (Miễn phí).
    * *Lý do lựa chọn:* Astra là một trong những theme miễn phí nhẹ nhất, tốc độ tải trang nhanh, khả năng tùy biến cao và tích hợp sâu với WooCommerce và các trình tạo trang, rất phù hợp để xây dựng một cửa hàng chuyên nghiệp mà không tốn chi phí.
* **Các Plugin Hỗ trợ chính:**
    * **Trình tạo trang:** Elementor (Bản miễn phí) - Giúp xây dựng trang chủ và các trang con một cách trực quan qua thao tác kéo-thả.
    * **Cổng thanh toán:**
        * WooCommerce MoMo Gateway (Chạy ở chế độ Test/Sandbox).
        * WooCommerce VNPay Gateway (Chạy ở chế độ Test/Sandbox).
        * Các phương thức mặc định: Chuyển khoản ngân hàng, Trả tiền mặt khi nhận hàng (COD).
    * **Vận chuyển:**
        * Cấu hình các vùng vận chuyển (Shipping Zones) mặc định của WooCommerce để demo các mức phí ship khác nhau (Nội thành, ngoại thành).
    * **Tối ưu SEO On-page:** Rank Math SEO.
    * **Tăng tốc độ (Caching):** LiteSpeed Cache (nếu hosting hỗ trợ) hoặc WP Super Cache.

#### 2.2. Kiến trúc Chức năng
Hệ thống bao gồm 2 nhóm chức năng chính:

**A. Chức năng dành cho Khách hàng (Front-end):**
1.  **Trang chủ:** Hiển thị sản phẩm nổi bật, sản phẩm mới, danh mục sản phẩm, banner khuyến mãi.
2.  **Trang Cửa hàng:** Liệt kê tất cả sản phẩm với bộ lọc thông minh (theo giá, danh mục, thuộc tính).
3.  **Trang Chi tiết sản phẩm:** Hiển thị đầy đủ thông tin, hình ảnh, mô tả, giá, đánh giá của khách hàng.
4.  **Tìm kiếm sản phẩm:** Cho phép người dùng tìm kiếm sản phẩm nhanh chóng theo tên.
5.  **Giỏ hàng:** Người dùng có thể thêm/xóa/cập nhật số lượng sản phẩm.
6.  **Thanh toán:**
    * Điền thông tin giao hàng.
    * Lựa chọn phương thức vận chuyển.
    * Lựa chọn phương thức thanh toán (COD, Chuyển khoản, MoMo/VNPay test).
7.  **Quản lý Tài khoản:**
    * Đăng ký / Đăng nhập.
    * Xem lịch sử đơn hàng.
    * Quản lý địa chỉ giao hàng.

**B. Chức năng dành cho Quản trị viên (Back-end):**
1.  **Bảng điều khiển (Dashboard):** Tổng quan nhanh về doanh thu, đơn hàng, tình trạng cửa hàng.
2.  **Quản lý Sản phẩm:** Thêm/sửa/xóa sản phẩm, danh mục sản phẩm, thuộc tính (màu sắc, kích thước).
3.  **Quản lý Đơn hàng:** Xem danh sách đơn hàng, cập nhật trạng thái (đang xử lý, đã giao, hủy...), in hóa đơn.
4.  **Quản lý Khách hàng:** Xem danh sách khách hàng và lịch sử mua hàng.
5.  **Quản lý Mã giảm giá:** Tạo và quản lý các chương trình coupon, khuyến mãi.
6.  **Xem Báo cáo:** Báo cáo doanh thu theo ngày/tháng, sản phẩm bán chạy.
7.  **Cài đặt Cửa hàng:** Cấu hình thông tin cửa hàng, tiền tệ, thuế, thanh toán, vận chuyển.

### 3. KẾ HOẠCH THỰC HIỆN (DỰ KIẾN)

| Giai đoạn | Nội dung công việc | Thời gian dự kiến |
| :--- | :--- | :--- |
| **Giai đoạn 1** | **Chuẩn bị và Cài đặt** <br> - Đăng ký hosting/cài đặt localhost. Cài đặt WordPress. <br> - Cài đặt Theme Astra và các plugin cần thiết (WooCommerce, Elementor...). | Tuần 1 |
| **Giai đoạn 2** | **Cấu hình hệ thống** <br> - Cấu hình thông tin cửa hàng, tiền tệ trong WooCommerce. <br> - Cấu hình các phương thức thanh toán (COD, Bank, MoMo/VNPay test). <br> - Cấu hình các vùng và mức phí vận chuyển demo. | Tuần 2 |
| **Giai đoạn 3** | **Xây dựng Giao diện & Nhập liệu** <br> - Thiết kế trang chủ, trang cửa hàng, trang liên hệ bằng Elementor. <br> - Tạo các danh mục sản phẩm. <br> - Nhập liệu mẫu cho khoảng 20-30 sản phẩm demo. | Tuần 3 - 4 |
| **Giai đoạn 4** | **Kiểm thử và Tối ưu** <br> - Thực hiện đặt hàng thử trên toàn bộ luồng. <br> - Kiểm tra hiển thị trên các thiết bị di động. <br> - Cấu hình plugin cache để tối ưu tốc độ. | Tuần 5 |
| **Giai đoạn 5** | **Hoàn thiện Báo cáo** <br> - Viết báo cáo đồ án, chụp ảnh màn hình các chức năng. <br> - Chuẩn bị cho buổi bảo vệ đồ án. | Tuần 6 |

### 4. DỮ LIỆU DEMO SỬ DỤNG
* **Sản phẩm mẫu:**
    * **Nguồn:** Lấy thông tin và hình ảnh sản phẩm minh họa từ các trang TMĐT lớn như Shopee, Tiki.
    * **Danh mục dự kiến:** Đồ điện tử, Thời trang Nam/Nữ, Đồ gia dụng.
    * **Thông tin:** Tên sản phẩm, mô tả, giá (giá thường, giá khuyến mãi), hình ảnh, kho hàng.
* **Tài khoản mẫu:**
    * **Admin:** `admin / [password]` - Để giảng viên đăng nhập và kiểm tra chức năng quản trị.
    * **Customer:** `khachhang / [password]` - Để demo luồng mua hàng của người dùng.

### 5. KẾT QUẢ DỰ KIẾN VÀ HƯỚNG PHÁT TRIỂN
* **Kết quả dự kiến:** Hoàn thành một website TMĐT hoạt động ổn định, đáp ứng đầy đủ các chức năng đã mô tả, giao diện trực quan, dễ sử dụng. Sản phẩm cuối cùng là một minh chứng cho việc nắm vững và ứng dụng thành công nền tảng WordPress/WooCommerce.
* **Hướng phát triển:**
    * Tích hợp chatbot hỗ trợ khách hàng.
    * Xây dựng hệ thống khách hàng thân thiết, tích điểm.
    * Phát triển tính năng so sánh sản phẩm.
    * Tối ưu SEO nâng cao để có thứ hạng trên công cụ tìm kiếm.