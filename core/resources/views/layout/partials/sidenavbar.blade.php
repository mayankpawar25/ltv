<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">

  <ul class="app-menu">
    <li><a class="app-menu__item @if(request()->path() == 'admin/dashboard') active @endif" href="{{route('admin.dashboard')}}"><i class="app-menu__icon fas fa-tachometer-alt"></i><span class="app-menu__label">Dashboard</span></a></li>

    @if(is_menu_enable(['gsettings', 'email_settings', 'sms_settings', 'crm_settings']))
    <li class="treeview
      @if(request()->path() == 'admin/generalSetting')
        is-expanded
      @elseif (request()->path() == 'admin/EmailSetting')
        is-expanded
      @elseif (request()->path() == 'admin/SmsSetting')
        is-expanded
      @endif">
      <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-globe"></i><span class="app-menu__label"> Control</span><i class="treeview-indicator fa fa-angle-right"></i></a>
      <ul class="treeview-menu">
        @if(is_menu_enable('gsettings'))
        <li><a class="treeview-item @if(request()->path() == 'admin/generalSetting') active @endif" href="{{route('admin.GenSetting')}}"><i class="icon far fa-circle"></i> General Setting</a></li>
        @endif
        
        @if(is_menu_enable('email_settings'))
          <li><a class="treeview-item @if(request()->path() == 'admin/EmailSetting') active @endif" href="{{route('admin.EmailSetting')}}" rel="noopener"><i class="icon far fa-circle"></i> Email Setting</a></li>
        @endif

        @if(is_menu_enable('sms_settings'))
          <li><a class="treeview-item @if(request()->path() == 'admin/SmsSetting') active @endif" href="{{route('admin.SmsSetting')}}"><i class="icon far fa-circle"></i> SMS Setting</a></li>
        @endif

        @if(is_menu_enable('crm_settings'))
          <li><a class="treeview-item @if(request()->path() == 'admin/settings') active @endif" href="{{ route('settings_main_page') }}"><i class="fas fa-cog menu-icon"></i>CRM Settings</a></li>
        @endif

       
      </ul>
    </li>
    @endif

    <!-- <li><a class="app-menu__item @if(request()->path() == 'admin/charge/index') active @endif" href="{{route('admin.charge.index')}}"><i class="app-menu__icon fas fa-money-bill-alt"></i><span class="app-menu__label">Charge Settings</span></a></li> -->

    @if(is_menu_enable('user_groups'))
    <li><a class="app-menu__item
      @if(request()->path() == 'admin/usergroup/index') active
      @elseif (request()->is('admin/options/*/index')) active
      @endif" href="{{route('admin.usergroup.index')}}"><i class="app-menu__icon fab fa-product-hunt"></i><span class="app-menu__label">User Groups</span></a></li>
    @endif

    @if(is_menu_enable('coupons'))
      <li><a class="app-menu__item @if(request()->path() == 'admin/coupon/index') active @endif" href="{{route('admin.coupon.index')}}"><i class="app-menu__icon fas fa-dollar-sign"></i><span class="app-menu__label">Coupon Settings</span></a></li>
    @endif

    @if(is_menu_enable('category_managements'))
      <li><a class="app-menu__item @if(request()->path() == 'admin/category/index' || request()->is('admin/subcategory/*')) active @endif" href="{{route('admin.category.index')}}"><i class="app-menu__icon fab fa-buromobelexperte"></i><span class="app-menu__label">Category Management</span></a></li>
    @endif

    @if(is_menu_enable('product_attr'))
    <li><a class="app-menu__item
      @if(request()->path() == 'admin/productattr/index') active
      @elseif (request()->is('admin/options/*/index')) active
      @endif" href="{{route('admin.productattr.index')}}"><i class="app-menu__icon fab fa-product-hunt"></i><span class="app-menu__label">Product Attributes</span></a></li>
    @endif

    @if(is_menu_enable('products'))
    <li><a class="app-menu__item
      @if(request()->path() == 'admin/product/index') active
      @elseif (request()->is('admin/options/*/index')) active
      @endif" href="{{route('admin.product.index')}}"><i class="app-menu__icon fab fa-product-hunt"></i><span class="app-menu__label">Product Management</span></a></li>
    @endif

    @if(is_menu_enable('shopkeepers'))
    <li><a class="app-menu__item
      @if(request()->path() == 'admin/shopkeeper/index') active
      @elseif (request()->is('admin/options/*/index')) active
      @endif" href="{{route('admin.shopkeeper.index')}}"><i class="app-menu__icon fab fa-product-hunt"></i><span class="app-menu__label">Dealer Management</span></a></li>
    @endif

    @if(is_menu_enable('job_cards'))
    <li><a class="app-menu__item
      @if(request()->path() == 'admin/jobcardtemplate/index') active
      @elseif (request()->is('admin/options/*/index')) active
      @endif" href="{{route('admin.jobcardtemplate.index')}}"><i class="app-menu__icon fab fa-product-hunt"></i><span class="app-menu__label">Job Card Templates</span></a></li>
    @endif

    <!-- <li><a class="app-menu__item @if(request()->path() == 'admin/packages') active @endif" href="{{route('admin.package')}}"><i class="app-menu__icon fa fa-gift"></i><span class="app-menu__label">Packages</span></a></li> -->


     <!--  <li class="treeview
      @if(request()->path() == 'admin/vendors/all')
        is-expanded
      @elseif(request()->path() == 'admin/vendors/pending')
          is-expanded
      @elseif(request()->path() == 'admin/vendors/accepted')
          is-expanded
      @elseif(request()->path() == 'admin/vendors/rejected')
          is-expanded
      @endif">
        <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-industry"></i><span class="app-menu__label">Vendor Requests</span><i class="treeview-indicator fa fa-angle-right"></i></a>
        <ul class="treeview-menu">
          <li><a class="treeview-item @if(request()->path() == 'admin/vendors/all') active @endif" href="{{route('admin.vendors.all')}}"><i class="icon far fa-circle"></i> All</a></li>
          <li><a class="treeview-item @if(request()->path() == 'admin/vendors/pending') active @endif" href="{{route('admin.vendors.pending')}}"><i class="icon far fa-circle"></i> Pending</a></li>
          <li><a class="treeview-item @if(request()->path() == 'admin/vendors/accepted') active @endif" href="{{route('admin.vendors.accepted')}}"><i class="icon far fa-circle"></i> Accepted</a></li>
          <li><a class="treeview-item @if(request()->path() == 'admin/vendors/rejected') active @endif" href="{{route('admin.vendors.rejected')}}"><i class="icon far fa-circle"></i> Rejected</a></li>
        </ul>
      </li> -->
    <!--   <li class="treeview
      @if(request()->path() == 'admin/flashsale/times')
        is-expanded
      @elseif(request()->path() == 'admin/flashsale/all')
          is-expanded
      @elseif(request()->path() == 'admin/flashsale/pending')
          is-expanded
      @elseif(request()->path() == 'admin/flashsale/accepted')
          is-expanded
      @elseif(request()->path() == 'admin/flashsale/rejected')
          is-expanded
      @endif">
        <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fas fa-hand-holding-usd"></i><span class="app-menu__label">Flash Sale</span><i class="treeview-indicator fa fa-angle-right"></i></a>
        <ul class="treeview-menu">
          <li><a class="treeview-item @if(request()->path() == 'admin/flashsale/times') active @endif" href="{{route('admin.flashsale.times')}}"><i class="icon far fa-circle"></i> Time Setup</a></li>
          <li><a class="treeview-item @if(request()->path() == 'admin/flashsale/all') active @endif" href="{{route('admin.flashsale.all')}}"><i class="icon far fa-circle"></i> All Flashsales</a></li>
          <li><a class="treeview-item @if(request()->path() == 'admin/flashsale/pending') active @endif" href="{{route('admin.flashsale.pending')}}"><i class="icon far fa-circle"></i> Pending Flashsales</a></li>
          <li><a class="treeview-item @if(request()->path() == 'admin/flashsale/accepted') active @endif" href="{{route('admin.flashsale.accepted')}}"><i class="icon far fa-circle"></i> Accepted Flashsales</a></li>
          <li><a class="treeview-item @if(request()->path() == 'admin/flashsale/rejected') active @endif" href="{{route('admin.flashsale.rejected')}}"><i class="icon far fa-circle"></i> Rejected Flashsales</a></li>
        </ul>
      </li> -->

    @if(is_menu_enable('orders'))
    <li class="treeview
      @if(request()->path() == 'admin/orders/all')
        is-expanded
      @elseif(request()->path() == 'admin/orders/confirmation/pending')
          is-expanded
      @elseif(request()->path() == 'admin/orders/confirmation/accepted')
          is-expanded
      @elseif(request()->path() == 'admin/orders/confirmation/rejected')
          is-expanded
      @elseif(request()->path() == 'admin/orders/delivery/pending')
          is-expanded
      @elseif(request()->path() == 'admin/orders/delivery/inprocess')
          is-expanded
      @elseif(request()->path() == 'admin/orders/delivered')
          is-expanded
      @elseif(request()->path() == 'admin/orders/cashondelivery')
          is-expanded
      @elseif(request()->path() == 'admin/orders/advance')
          is-expanded
      @endif">
      <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-shopping-cart"></i><span class="app-menu__label">Orders</span><i class="treeview-indicator fa fa-angle-right"></i></a>
      <ul class="treeview-menu">
        <li><a class="treeview-item @if(request()->path() == 'admin/orders/all') active @endif" href="{{route('admin.orders.all')}}"><i class="icon far fa-circle"></i> All</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/orders/confirmation/pending') active @endif" href="{{route('admin.orders.cPendingOrders')}}"><i class="icon far fa-circle"></i> Pending</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/orders/confirmation/accepted') active @endif" href="{{route('admin.orders.cAcceptedOrders')}}"><i class="icon far fa-circle"></i> Accepted</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/orders/confirmation/rejected') active @endif" href="{{route('admin.orders.cRejectedOrders')}}"><i class="icon far fa-circle"></i> Rejected</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/orders/delivery/pending') active @endif" href="{{route('admin.orders.pendingDelivery')}}"><i class="icon far fa-circle"></i> Delivery Pending</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/orders/delivery/inprocess') active @endif" href="{{route('admin.orders.pendingInprocess')}}"><i class="icon far fa-circle"></i> Delivery Inprocess</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/orders/delivered') active @endif" href="{{route('admin.orders.delivered')}}"><i class="icon far fa-circle"></i> Delivered</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/orders/cashondelivery') active @endif" href="{{route('admin.orders.cashOnDelivery')}}"><i class="icon far fa-circle"></i> Cash on Delivery</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/orders/advance') active @endif" href="{{route('admin.orders.advance')}}"><i class="icon far fa-circle"></i> Advance Paid</a></li>
      </ul>
    </li>
    @endif


    <!--   <li class="treeview
      @if(request()->path() == 'admin/comments')
        is-expanded
      @elseif(request()->path() == 'admin/complains')
        is-expanded
      @elseif(request()->path() == 'admin/suggestions')
        is-expanded
      @endif">
      <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-comments"></i><span class="app-menu__label">Comments</span><i class="treeview-indicator fa fa-angle-right"></i></a>
      <ul class="treeview-menu">
        <li><a class="treeview-item @if(request()->path() == 'admin/comments') active @endif" href="{{route('admin.comments.all')}}"><i class="icon far fa-circle"></i> All</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/complains') active @endif" href="{{route('admin.complains')}}"><i class="icon far fa-circle"></i> Complains</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/suggestions') active @endif" href="{{route('admin.suggestions')}}"><i class="icon far fa-circle"></i> Suggestions</a></li>
      </ul>
    </li> -->


    <!-- <li class="treeview
      @if(request()->path() == 'admin/refunds/all')
        is-expanded
      @elseif(request()->path() == 'admin/refunds/pending')
          is-expanded
      @elseif(request()->path() == 'admin/refunds/rejected')
          is-expanded
      @elseif(request()->path() == 'admin/refunds/accepted')
          is-expanded
      @endif">
      <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-undo"></i><span class="app-menu__label">Refund Request</span><i class="treeview-indicator fa fa-angle-right"></i></a>
      <ul class="treeview-menu">
        <li><a class="treeview-item @if(request()->path() == 'admin/refunds/all') active @endif" href="{{route('admin.refunds.all')}}"><i class="icon far fa-circle"></i> All</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/refunds/pending') active @endif" href="{{route('admin.refunds.pending')}}"><i class="icon far fa-circle"></i> Pending</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/refunds/accepted') active @endif" href="{{route('admin.refunds.accepted')}}"><i class="icon far fa-circle"></i> Accepted</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/refunds/rejected') active @endif" href="{{route('admin.refunds.rejected')}}"><i class="icon far fa-circle"></i> Rejected</a></li>
      </ul>
    </li> -->

    @if(is_menu_enable('users'))
    <!-- <li class="treeview
      @if (request()->path() == 'admin/userManagement/allUsers')
        is-expanded
      @elseif (request()->path() == 'admin/userManagement/bannedUsers')
        is-expanded
      @elseif (request()->path() == 'admin/userManagement/verifiedUsers')
        is-expanded
      @elseif (request()->path() == 'admin/userManagement/mobileUnverifiedUsers')
        is-expanded
      @elseif (request()->path() == 'admin/userManagement/emailUnverifiedUsers')
        is-expanded
      @elseif (request()->is('admin/userManagement/userDetails/*'))
        is-expanded
      @elseif (request()->is('admin/userManagement/emailToUser/*'))
        is-expanded
      @elseif (request()->path() == 'admin/userManagement/allUsersSearchResult')
        is-expanded
      @elseif (request()->path() == 'admin/userManagement/bannedUsersSearchResult')
        is-expanded
      @elseif (request()->path() == 'admin/userManagement/verUsersSearchResult')
        is-expanded
      @elseif (request()->path() == 'admin/userManagement/mobileUnverifiedUsersSearchResult')
        is-expanded
      @elseif (request()->path() == 'admin/userManagement/emailUnverifiedUsersSearchResult')
        is-expanded
      @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-users"></i><span class="app-menu__label">Customer</span><i class="treeview-indicator fa fa-angle-right"></i></a>
      <ul class="treeview-menu"> -->
        <!-- <li><a class="treeview-item @if(request()->path() == 'admin/userManagement/allUsers' || request()->path() == 'admin/userManagement/allUsersSearchResult') active @endif" href="{{route('admin.allUsers')}}"><i class="icon far fa-circle"></i> All Users</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/userManagement/bannedUsers' || request()->path() == 'admin/userManagement/bannedUsersSearchResult') active @endif" href="{{route('admin.bannedUsers')}}"><i class="icon far fa-circle"></i> Banned Users</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/userManagement/verifiedUsers' || request()->path() == 'admin/userManagement/verUsersSearchResult') active @endif" href="{{route('admin.verifiedUsers')}}"><i class="icon far fa-circle"></i> Verified Users</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/userManagement/mobileUnverifiedUsers' || request()->path() == 'admin/userManagement/mobileUnverifiedUsersSearchResult') active @endif" href="{{route('admin.mobileUnverifiedUsers')}}"><i class="icon far fa-circle"></i> Mobile Unverified Users</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/userManagement/emailUnverifiedUsers' || request()->path() == 'admin/userManagement/emailUnverifiedUsersSearchResult') active @endif" href="{{route('admin.emailUnverifiedUsers')}}"><i class="icon far fa-circle"></i> Email Unverified Users</a></li> -->

     <!--     <li><a class="treeview-item @if(request()->path() == 'admin/customers' || request()->path() == 'admin/customers') active @endif" href="{{route('customers_list')}}"><i class="icon far fa-circle"></i> CRM User List</a></li>
      </ul>
    </li> -->
    @endif

    @if(is_menu_enable('users'))
      <li><a class="app-menu__item @if(request()->path() == 'admin/customers' || request()->is('admin/customers/*')) active @endif" href="{{route('customers_list')}}"><i class="app-menu__icon fa fa-users"></i><span class="app-menu__label">Customer Management</span></a></li>
    @endif


    <!-- <li class="treeview
      @if (request()->path() == 'admin/vendorManagement/allVendors')
        is-expanded
      @elseif (request()->path() == 'admin/vendorManagement/bannedVendors')
        is-expanded
      @elseif (request()->is('admin/vendorManagement/emailToVendor/*'))
        is-expanded
      @elseif (request()->is('admin/vendorManagement/addSubtractBalance/*'))
        is-expanded
      @elseif (request()->path() == 'admin/vendorManagement/allVendorsSearchResult')
        is-expanded
      @elseif (request()->path() == 'admin/vendorManagement/bannedVendorsSearchResult')
        is-expanded
      @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-users"></i><span class="app-menu__label">Vendors Management</span><i class="treeview-indicator fa fa-angle-right"></i></a>
      <ul class="treeview-menu">
        <li><a class="treeview-item @if(request()->path() == 'admin/vendorManagement/allVendors' || request()->path() == 'admin/vendorManagement/allVendorsSearchResult') active @endif" href="{{route('admin.allVendors')}}"><i class="icon far fa-circle"></i> All Vendors</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/vendorManagement/bannedVendors' || request()->path() == 'admin/vendorManagement/bannedVendorsSearchResult') active @endif" href="{{route('admin.bannedVendors')}}"><i class="icon far fa-circle"></i> Banned Vendors</a></li>
      </ul>
    </li> -->
    <!--  <li><a class="app-menu__item @if(request()->path() == 'admin/subscribers') active @endif" href="{{route('admin.subscribers')}}"><i class="app-menu__icon fas fa-newspaper"></i><span class="app-menu__label">Subscribers</span></a></li> -->

    @if(is_menu_enable('gateways'))
    <li><a class="app-menu__item @if(request()->path() == 'admin/gateways') active @endif" href="{{route('admin.gateways')}}"><i class="app-menu__icon fab fa-cc-mastercard"></i><span class="app-menu__label">Gateways</span></a></li>
    @endif

    <!-- <li class="treeview
      @if(request()->path() == 'admin/deposit/pending')
        is-expanded
      @elseif (request()->path() == 'admin/deposit/acceptedRequests')
        is-expanded
      @elseif (request()->path() == 'admin/deposit/rejectedRequests')
        is-expanded
      @elseif (request()->path() == 'admin/deposit/depositLog')
        is-expanded
      @endif">
      <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-download"></i><span class="app-menu__label">Deposit</span><i class="treeview-indicator fa fa-angle-right"></i></a>
      <ul class="treeview-menu">
        <li><a class="treeview-item @if(request()->path() == 'admin/deposit/pending') active @endif" href="{{route('admin.deposit.pending')}}"><i class="icon far fa-circle"></i> Pending Request</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/deposit/acceptedRequests') active @endif" href="{{route('admin.deposit.acceptedRequests')}}" rel="noopener"><i class="icon far fa-circle"></i> Accepted Request</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/deposit/rejectedRequests') active @endif" href="{{route('admin.deposit.rejectedRequests')}}"><i class="icon far fa-circle"></i> Rejected Request</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/deposit/depositLog') active @endif" href="{{route('admin.deposit.depositLog')}}"><i class="icon far fa-circle"></i> Deposit Log</a></li>
      </ul>
    </li> -->


    <!--  <li class="treeview
      @if(request()->path() == 'admin/withdrawLog')
        is-expanded
      @elseif (request()->path() == 'admin/withdrawMethod')
        is-expanded
      @elseif (request()->path() == 'admin/successLog')
        is-expanded
      @elseif (request()->path() == 'admin/refundedLog')
        is-expanded
      @elseif (request()->path() == 'admin/pendingLog')
        is-expanded
      @endif">
      <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-upload"></i><span class="app-menu__label">Withdraw Money</span><i class="treeview-indicator fa fa-angle-right"></i></a>
      <ul class="treeview-menu">
        <li><a class="treeview-item @if(request()->path() == 'admin/withdrawMethod') active @endif" href="{{route('admin.withdrawMethod')}}"><i class="icon far fa-circle"></i> Withdraw Method</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/withdrawLog') active @endif" href="{{route('admin.withdrawLog')}}" rel="noopener"><i class="icon far fa-circle"></i> Withdraw Log</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/pendingLog') active @endif" href="{{route('admin.withdrawMoney.pendingLog')}}"><i class="icon far fa-circle"></i> Pending Requests Log</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/successLog') active @endif" href="{{route('admin.withdrawMoney.successLog')}}"><i class="icon far fa-circle"></i> Success Log</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/refundedLog') active @endif" href="{{route('admin.withdrawMoney.refundedLog')}}"><i class="icon far fa-circle"></i> Refunded Log</a></li>
      </ul>
    </li> -->

    <!-- <li><a class="app-menu__item @if(request()->path() == 'admin/trxlog') active @endif" href="{{route('admin.trxLog')}}"><i class="app-menu__icon fas fa-exchange-alt"></i><span class="app-menu__label">Transaction Log</span></a></li> -->


    <li class="treeview
      @if (request()->path() == 'admin/interfaceControl/logoIcon/index')
        is-expanded
      @elseif (request()->path() == 'admin/interfaceControl/partner/index')
        is-expanded
      @elseif (request()->path() == 'admin/interfaceControl/slider/index')
        is-expanded
      @elseif (request()->path() == 'admin/interfaceControl/contact/index')
        is-expanded
      @elseif (request()->path() == 'admin/interfaceControl/support/index')
        is-expanded
      @elseif (request()->path() == 'admin/interfaceControl/social/index')
        is-expanded
      @elseif (request()->path() == 'admin/interfaceControl/footer/index')
        is-expanded
      @elseif (request()->path() == 'admin/interfaceControl/logintext/index')
        is-expanded
      @elseif (request()->path() == 'admin/interfaceControl/registertext/index')
        is-expanded
      @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-desktop"></i><span class="app-menu__label">Interface Control</span><i class="treeview-indicator fa fa-angle-right"></i></a>
      <ul class="treeview-menu">
        <li><a class="treeview-item @if(request()->path() == 'admin/interfaceControl/logoIcon/index') active @endif" href="{{route('admin.logoIcon.index')}}"><i class="icon far fa-circle"></i> Logo+Icon Setting</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/interfaceControl/support/index') active @endif" href="{{route('admin.support.index')}}"><i class="icon far fa-circle"></i> Support Informations</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/interfaceControl/partner/index') active @endif" href="{{route('admin.partner.index')}}"><i class="icon far fa-circle"></i> Partners</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/interfaceControl/slider/index') active @endif" href="{{route('admin.slider.index')}}"><i class="icon far fa-circle"></i> Slider Settings</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/interfaceControl/contact/index') active @endif" href="{{route('admin.contact.index')}}"><i class="icon far fa-circle"></i> Contact Informations</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/interfaceControl/social/index') active @endif" href="{{route('admin.social.index')}}"><i class="icon far fa-circle"></i> Social Links Setting</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/interfaceControl/logintext/index') active @endif" href="{{route('admin.logintext.index')}}"><i class="icon far fa-circle"></i> Login Text</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/interfaceControl/registertext/index') active @endif" href="{{route('admin.registertext.index')}}"><i class="icon far fa-circle"></i> Register Text</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/interfaceControl/footer/index') active @endif" href="{{route('admin.footer.index')}}"><i class="icon far fa-circle"></i> Footer Text</a></li>
      </ul>
    </li>


    <!-- <li class="treeview
      @if(request()->path() == 'admin/policy/refund/index')
        is-expanded
      @elseif(request()->path() == 'admin/tos/index')
        is-expanded
      @elseif(request()->path() == 'admin/policy/replacement/index')
        is-expanded
      @elseif(request()->path() == 'admin/privacy/index')
        is-expanded
      @endif">
      <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fas fa-clipboard-list"></i><span class="app-menu__label">Policy</span><i class="treeview-indicator fa fa-angle-right"></i></a>
      <ul class="treeview-menu">
        <li><a class="treeview-item @if(request()->path() == 'admin/policy/refund/index') active @endif" href="{{route('admin.refund.index')}}"><i class="icon far fa-circle"></i> Refund Policy</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/policy/replacement/index') active @endif" href="{{route('admin.replacement.index')}}"><i class="icon far fa-circle"></i> Replacement Policy</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/tos/index') active @endif" href="{{route('admin.tos.index')}}"><i class="icon far fa-circle"></i> Terms & Conditions</a></li>
        <li><a class="treeview-item @if(request()->path() == 'admin/privacy/index') active @endif" href="{{route('admin.privacy.index')}}"><i class="icon far fa-circle"></i> Privacy Policy</a></li>
      </ul>
    </li> -->


    <!--  <li><a class="app-menu__item @if(request()->path() == 'admin/menuManager/index') active @endif" href="{{route('admin.menuManager.index')}}"><i class="app-menu__icon fa fa-bars"></i><span class="app-menu__label">Menu Management</span></a></li> -->


    <!-- <li><a class="app-menu__item
      @if(request()->path() == 'admin/Ad/index')
        active
      @elseif(request()->path() == 'admin/Ad/create')
        active
      @endif" href="{{route('admin.ad.index')}}"><i class="app-menu__icon fab fa-buysellads"></i> <span class="app-menu__label"> Advertisement</span></a>
    </li> -->

    @if(is_menu_enable('gateways'))
<!--     <li class="treeview
      @if (request()->path() == 'admin/salesman/index')
        is-expanded
      @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-users"></i><span class="app-menu__label">Salesman Management</span><i class="treeview-indicator fa fa-angle-right"></i></a>
      <ul class="treeview-menu">
        <li><a class="treeview-item @if(request()->path() == 'admin/salesman/index' ) active @endif" href="{{route('admin.salesman.index')}}"><i class="icon far fa-circle"></i> Add Salesman</a></li>
      </ul>
    </li> -->
    @endif

    @if(is_menu_enable('leads'))
    <li class="treeview
      @if (request()->path() == 'admin/leads/index')
        is-expanded
      @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-users"></i><span class="app-menu__label">Leads Management</span><i class="treeview-indicator fa fa-angle-right"></i></a>
      <ul class="treeview-menu">
        <li><a class="treeview-item @if(request()->path() == 'admin/leads/index' ) active @endif" href="{{route('leads_list')}}"><i class="icon far fa-circle"></i> All Leads</a></li>
      </ul>
    </li>
    @endif

    @if(is_menu_enable(['team_members','teams']))
    <li class="treeview
      @if (request()->path() == 'admin/teams')
        is-expanded
      @elseif(request()->path() == 'admin/teams/members')
        is-expanded
      @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-users"></i><span class="app-menu__label">Team & Members</span><i class="treeview-indicator fa fa-angle-right"></i></a>
      <ul class="treeview-menu">
        @if(is_menu_enable('teams'))
        <li><a class="treeview-item @if(request()->path() == 'admin/teams' ) active @endif" href="{{route('teams_list')}}"><i class="icon far fa-circle"></i>Team</a></li>
        @endif

        @if(is_menu_enable('team_members'))
        <li><a class="treeview-item @if(request()->path() == 'admin/teams/members' ) active @endif" href="{{route('team_members_list')}}"><i class="icon far fa-circle"></i>Members</a></li>
        @endif
      </ul>
    </li>
    @endif
    
    @if(is_menu_enable('tasks'))
    <li class="treeview
      @if (request()->path() == 'admin/tasks/salesman')
        is-expanded
      @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-users"></i><span class="app-menu__label">Tasks Management</span><i class="treeview-indicator fa fa-angle-right"></i></a>
      <ul class="treeview-menu">
        <li><a class="treeview-item @if(request()->path() == 'admin/tasks/salesman' ) active @endif" href="{{route('admin.tasks.salesmanlist')}}"><i class="icon far fa-circle"></i> All Task</a></li>
      </ul>
    </li>
    @endif

    @if(is_menu_enable('expenses'))
    <li class="treeview
      @if (request()->path() == 'admin/expenses')
        is-expanded
      @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-users"></i><span class="app-menu__label">Expenses Management</span><i class="treeview-indicator fa fa-angle-right"></i></a>
      <ul class="treeview-menu">
        <li><a class="treeview-item @if(request()->path() == 'admin/expenses' ) active @endif" href="{{route('expense_list')}}"><i class="icon far fa-circle"></i> All Expenses</a></li>
      </ul>
    </li>
    @endif

   @if(is_menu_enable(['proposals','estimates','invoices' ,'payments']))
    <li class="treeview
      @if (request()->path() == 'admin/proposals')
        is-expanded
      @elseif (request()->path() == 'admin/proposals/create')
        is-expanded
      @elseif (request()->path() == 'admin/estimates')
        is-expanded
      @elseif (request()->path() == 'admin/estimates/create')
        is-expanded
      @elseif (request()->path() == 'admin/invoices')
        is-expanded
      @elseif (request()->path() == 'admin/invoices/create')
        is-expanded
      @elseif (request()->path() == 'admin/payments')
        is-expanded
     
      @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-desktop"></i><span class="app-menu__label">Sales Management</span><i class="treeview-indicator fa fa-angle-right"></i></a>
      <ul class="treeview-menu">

        @if(is_menu_enable('proposals'))
        <li><a class="treeview-item @if(request()->path() == 'admin/proposals' || request()->path() == 'admin/proposals/create') active @endif" href="{{route('proposal_list')}}"><i class="icon far fa-circle"></i> Proposals</a></li>
        @endif

        @if(is_menu_enable('estimates'))
        <li><a class="treeview-item @if(request()->path() == 'admin/estimates' || request()->path() == 'admin/estimates/create') active @endif" href="{{route('estimate_list')}}"><i class="icon far fa-circle"></i> Estimates</a></li>
        @endif

        @if(is_menu_enable('invoices'))
        <li><a class="treeview-item @if(request()->path() == 'admin/invoices' || request()->path() == 'admin/invoices/create') active @endif" href="{{route('invoice_list')}}"><i class="icon far fa-circle"></i> Invoices</a></li>
        @endif

        @if(is_menu_enable('payments'))
        <li><a class="treeview-item @if(request()->path() == 'admin/payments' || request()->path() == 'admin/payments') active @endif" href="{{route('payment_list')}}"><i class="icon far fa-circle"></i> Payments</a></li>
        @endif

      </ul>
    </li>
    @endif

    

    <li><a class="app-menu__item @if(request()->path() == 'admin/salesscores') active @endif" href="{{route('admin.salesman.salesscore')}}"><i class="app-menu__icon fas fa-exchange-alt"></i><span class="app-menu__label">Sales Score</span></a></li>

   
   <!--  @if(is_menu_enable('tickets'))
    <li class="treeview
      @if (request()->path() == 'admin/tickets')
        is-expanded
      @elseif (request()->path() == 'admin/tickets/create')
        is-expanded
      @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-desktop"></i><span class="app-menu__label">Support Management</span><i class="treeview-indicator fa fa-angle-right"></i></a>
      <ul class="treeview-menu">
        <li><a class="treeview-item @if(request()->path() == 'admin/tickets' || request()->path() == 'admin/tickets/create') active @endif" href="{{route('ticket_list')}}"><i class="icon far fa-circle"></i> All Tickets</a></li>
      </ul>
    </li>
    @endif -->

     @if(is_menu_enable(['sales_report','leads_report' ,'activity_log']))
    <li class="treeview
      @if (request()->path() == 'admin/reports/sales')
        is-expanded
      @elseif (request()->path() == 'admin/reports/expenses')
        is-expanded
      @elseif (request()->path() == 'admin/reports/leads')
        is-expanded
      @elseif (request()->path() == 'admin/reports/activity-log')
        is-expanded
     
      @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-desktop"></i><span class="app-menu__label">Reports Management</span><i class="treeview-indicator fa fa-angle-right"></i></a>
      <ul class="treeview-menu">

       <!--  @if(is_menu_enable('sales_report'))
        <li><a class="treeview-item @if(request()->path() == 'admin/reports/sales' || request()->path() == 'admin/reports/sales') active @endif" href="{{route('report_sales_page')}}"><i class="icon far fa-circle"></i> Sales Report</a></li>
        @endif -->

        @if(is_menu_enable('expenses'))
        <li><a class="treeview-item @if(request()->path() == 'admin/reports/expenses' || request()->path() == 'admin/reports/expenses') active @endif" href="{{route('report_expenses_page')}}"><i class="icon far fa-circle"></i> Expense Report</a></li>
        @endif

        @if(is_menu_enable('leads_report'))
        <li><a class="treeview-item @if(request()->path() == 'admin/reports/leads' || request()->path() == 'admin/reports/leads') active @endif" href="{{route('lead_report_page')}}"><i class="icon far fa-circle"></i> Leads Report</a></li>
        @endif

        @if(is_menu_enable('activity_log'))
        <li><a class="treeview-item @if(request()->path() == 'admin/reports/activity-log' || request()->path() == 'admin/reports/activity-log') active @endif" href="{{route('report_activity_log')}}"><i class="icon far fa-circle"></i>Activity Log</a></li>
       @endif

       </ul>
    </li>
    @endif



    <li class="treeview
      @if (request()->path() == 'admin/countries')
        is-expanded
      @elseif (request()->segment(2)== 'countries')
        is-expanded
      @elseif (request()->path() == 'admin/states')
        is-expanded
      @elseif (request()->segment(2)== 'states')
        is-expanded
      @elseif (request()->path() == 'admin/cities')
        is-expanded
      @elseif (request()->segment(2)== 'cities')
        is-expanded
     
      @endif">
      <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-globe"></i><span class="app-menu__label">Location</span><i class="treeview-indicator fa fa-angle-right"></i></a>
      <ul class="treeview-menu">

         
        <li><a class="treeview-item @if(request()->path() == 'admin/countries' || request()->segment(2)== 'countries') active @endif" href="{{route('countries.index')}}"><i class="icon far fa-circle"></i>Countries</a></li>

        <li><a class="treeview-item @if(request()->path() == 'admin/states' || request()->segment(2)== 'states') active @endif" href="{{route('states.index')}}"><i class="icon far fa-circle"></i>States</a></li>

         <li><a class="treeview-item @if(request()->path() == 'admin/cities' || request()->segment(2)== 'cities') active @endif" href="{{route('cities.index')}}"><i class="icon far fa-circle"></i>Cities</a></li>

         <li><a class="treeview-item @if(request()->path() == 'admin/area' || request()->segment(2)== 'area') active @endif" href="{{route('area.index')}}"><i class="icon far fa-circle"></i>Area</a></li>
       
       </ul>
    </li>
   


  </ul>
</aside>
