<script>
$(document).ready(function(){
    $("#{{ Request::segment(2) }}").addClass('active');
});
</script>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">ADMIN</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="" id="dashboard" class="nav-link ">
                        <i class="nav-icon fa fa-dashboard"></i>
                        <p>Bảng quản trị

                        </p>
                    </a>
                </li>

                <!-- Softcard -->
                <li class="nav-item has-treeview">
                    <a href="#" id="users" class="nav-link">
                        <i class="nav-icon fa fa-credit-card"></i>
                        <p>Mã thẻ cào<i class="right fa fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url($backendUrl.'/softcard') }}" class="nav-link">
                                <i class="fa fa-angle-right nav-icon"></i>
                                <p>Sản phẩm</p>
                            </a>
                        </li>
                    </ul>
                </li>


                <!-- Stockcard -->
                <li class="nav-item has-treeview">
                    <a href="#" id="users" class="nav-link">
                        <i class="nav-icon fa fa-database"></i>
                        <p>Kho thẻ<i class="right fa fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url($backendUrl.'/stockcards') }}" class="nav-link">
                                <i class="fa fa-angle-right nav-icon"></i>
                                <p>Kho</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url($backendUrl.'/stockcards/setting') }}" class="nav-link">
                                <i class="fa fa-angle-right nav-icon"></i>
                                <p>Cấu hình kho</p>
                            </a>
                        </li>
                    </ul>
                </li>


                <!-- Mtopup -->
                <li class="nav-item has-treeview">
                    <a href="{{ url($backendUrl.'/mtopup') }}" id="paygates" class="nav-link">
                        <i class="nav-icon fa fa-plug" aria-hidden="true"></i>
                        <p>Nạp topup chậm</p>
                    </a>
                </li>


                <!-- Charging -->
                <li class="nav-item has-treeview">
                    <a href="{{ url($backendUrl.'/chargings') }}" id="paygates" class="nav-link">
                        <i class="nav-icon fa fa-paypal" aria-hidden="true"></i>
                        <p>Tẩy thẻ chậm</p>
                    </a>
                </li>

                <!-- Charging -->
                <li class="nav-item has-treeview">
                    <a href="{{ url($backendUrl.'/autochargings') }}" id="paygates" class="nav-link">
                        <i class="nav-icon fa fa-paypal" aria-hidden="true"></i>
                        <p>Tẩy thẻ nhanh</p>
                    </a>
                </li>

                <!-- News -->
                <li class="nav-item has-treeview">
                    <a href="news/" id="users" class="nav-link">
                        <i class="nav-icon fa fa-newspaper-o"></i>
                        <p>Tin tức</p>
                    </a>
                </li>


                <!-- User -->
                <li class="nav-item has-treeview">
                    <a href="users/" id="users" class="nav-link">
                        <i class="nav-icon fa fa-users"></i>
                        <p>Khách hàng</p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="users/" class="nav-link">
                                <i class="fa fa-angle-right nav-icon"></i>
                                <p>Danh sách</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="groups/" class="nav-link">
                                <i class="fa fa-angle-right nav-icon"></i>
                                <p>Nhóm</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="roles/" class="nav-link">
                                <i class="fa fa-angle-right nav-icon"></i>
                                <p>Chức danh</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="permissions" class="nav-link">
                                <i class="fa fa-angle-right nav-icon"></i>
                                <p>Quyền hạn</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <!-- Wallet-->
                <li class="nav-item has-treeview">
                    <a href="wallet-settings/" id="users" class="nav-link">
                        <i class="nav-icon fa fa-google-wallet"></i>
                        <p>Ví điện tử<i class="right fa fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="wallets" class="nav-link">
                                <i class="fa fa-angle-right nav-icon"></i>
                                <p>Danh sách ví</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="wallet-fees/" class="nav-link">
                                <i class="fa fa-angle-right nav-icon"></i>
                                <p>Phí thanh toán</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="wallet-settings/" class="nav-link">
                                <i class="fa fa-angle-right nav-icon"></i>
                                <p>Cấu hình ví</p>
                            </a>
                        </li>
                    </ul>
                </li>


                <!-- Localbank-->
                <li class="nav-item has-treeview">
                    <a href="#" id="users" class="nav-link">
                        <i class="nav-icon fa fa-university"></i>
                        <p>Ngân hàng<i class="right fa fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url($backendUrl.'/localbank') }}" class="nav-link">
                                <i class="fa fa-angle-right nav-icon"></i>
                                <p>Của hệ thống</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url($backendUrl.'/localbank/users') }}" class="nav-link">
                                <i class="fa fa-angle-right nav-icon"></i>
                                <p>Của khách hàng</p>
                            </a>
                        </li>
                    </ul>
                </li>


                <!-- Other Module-->
                <li class="nav-item has-treeview">
                    <a href="#" id="users" class="nav-link">
                        <i class="nav-icon fa fa-cubes"></i>
                        <p>Mô-đun khác<i class="right fa fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url($backendUrl.'/menu') }}" class="nav-link">
                                <i class="fa fa-angle-right nav-icon"></i>
                                <p>Menu</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url($backendUrl.'/sliders') }}" class="nav-link">
                                <i class="fa fa-angle-right nav-icon"></i>
                                <p>Trình diễn ảnh</p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="{{ url($backendUrl.'/categories') }}" class="nav-link">
                                <i class="fa fa-angle-right nav-icon"></i>
                                <p>Categories</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url($backendUrl.'/weblinks') }}" class="nav-link">
                                <i class="fa fa-angle-right nav-icon"></i>
                                <p>Trao đổi banner</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url($backendUrl.'/merchants') }}" class="nav-link">
                                <i class="fa fa-angle-right nav-icon"></i>
                                <p>Đối tác kết nối</p>
                            </a>
                        </li>
                    </ul>
                </li>


                <!-- Setting -->
                <li class="nav-item has-treeview">
                    <a href="#" id="users" class="nav-link">
                        <i class="nav-icon fa fa-cog"></i>
                        <p>Cấu hình hệ thống<i class="right fa fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url($backendUrl.'/settings/general') }}" class="nav-link">
                                <i class="fa fa-angle-right nav-icon"></i>
                                <p>Cài đặt</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url($backendUrl.'/currencies') }}" class="nav-link">
                                <i class="fa fa-angle-right nav-icon"></i>
                                <p>Tiền tệ</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url($backendUrl.'/paygates') }}" class="nav-link">
                                <i class="fa fa-angle-right nav-icon"></i>
                                <p>Cổng thanh toán</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url($backendUrl.'/medias') }}" class="nav-link">
                                <i class="fa fa-angle-right nav-icon"></i>
                                <p>Thư viện ảnh</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url($backendUrl.'/translations') }}" class="nav-link">
                                <i class="fa fa-angle-right nav-icon"></i>
                                <p>Ngôn ngữ</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url($backendUrl.'/sendmail/setting') }}" class="nav-link">
                                <i class="fa fa-angle-right nav-icon"></i>
                                <p>Cấu hình mail</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url($backendUrl.'/tagslist') }}" class="nav-link">
                                <i class="fa fa-angle-right nav-icon"></i>
                                <p>Tags</p>
                            </a>
                        </li>


                    </ul>
                </li>





            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
